<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Prospect;
use App\Models\Manager;
use App\Models\Tracking;
use App\Models\Offer;
use App\Models\ProspectComments;
use DB;
use Illuminate\Support\Facades\Redirect;

class ProspectController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('manager.prospects.index');
    }
    
    /**
    * New index function working with datatables.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getProspects(Request $request)
    {
        if ($request->ajax()) {
            
            if (Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["type"] == "LM") {
                $data = Prospect::latest()->where('type', '=', 'Carrier')->get();
            } else if (Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["type"] == "TM") {
                $data = Prospect::latest()->where('type', '=', 'Client')->get();
            }
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('name', function($row)
                {
                    if(isset($row->unavailable_until) && $row->unavailable_until > date("Y-m-d H:i:s"))
                        return "<div class='fw-bold text-warning'> ". $row->name ." </div>";
                    switch ($row->state) {
                        case "2":
                        case "3":
                            return "<div class='fw-bold text-danger'> ". $row->name ." </div>";
                        case "4":
                            return "<div class='fw-bold text-success'>". $row->name ."</div>";    
                        default:
                            return $row->name;
                        
                    }
                })
                ->editColumn('country', function($row)
                {
                    return countryCodeToEmojiName($row->country);
                })
                ->editColumn('email', function($row)
                {
                    $actionBtn = '<a role="button" class="bi bi-clipboard" style="font-size: 1.3rem; white-space: nowrap;" onclick="let ref = getElementById(\'mailCopy\'); ref.value = \''.$row->email.'\'; ref.style.display=\'block\'; ref.select(); document.execCommand(\'copy\'); ref.style.display=\'none\'; ref.value =\'\';"></a>';
                    return $actionBtn . ' ' . $row->email;
                })
                ->editColumn('state', function($row)
                {
                    if(isset($row->unavailable_until) && $row->unavailable_until > date("Y-m-d H:i:s"))
                        return "On Stand-By";
                    return getStateToHuman($row->state);
                })
                ->editColumn('created_at', function($row)
                {
                   $created_at = $row->created_at->format('Y-m-d');
                   return $created_at;
                })
                ->editColumn('deadline', function($row)
                {
                    if (isset($row->deadline)) {
                        $deadline = $row->deadline->format('Y-m-d');
                        return $deadline;
                    }
                })
                ->addColumn('actor', function($row) {
                    $managerName = DB::table('managers')->select('first_name', 'last_name')->where('id', $row->actor)->get();
                    foreach($managerName as $name)
                        return $name->first_name.' '.$name->last_name;
                })
                ->addColumn('action', function($row)
                {
                    $actionBtn = '<div class="d-flex justify-content-center align-items-center"><a href="prospect/'.$row->id.'" role="button" class="bi bi-eye" style="font-size: 1.8rem;"></a></div>';
                    return $actionBtn;
                }) 
                ->addColumn('counter', function($row)
                {
                    $nb = Tracking::all()->where('id_prospect', '=', $row->id)->count();
                    if($nb <= 0) {
                        $countDisplay = "<span class='fw-bold' style='color: #6610f2'>&nbsp;&nbsp;".$nb."</span>";
                    } else if ($nb === 1) {
                        $countDisplay = "<span class='text-success fw-bold'>&nbsp;&nbsp;".$nb."</span>";
                    } else if ($nb > 1 && $nb < 6) {
                        $countDisplay = "<span class='text-warning fw-bold'>&nbsp;&nbsp;".$nb."</span>";
                    } else if ($nb > 6) {
                        $countDisplay = "<span class='text-danger fw-bold'>&nbsp;&nbsp;".$nb."</span>";
                    }
                    return $countDisplay;
                })
                ->rawColumns(['name', 'email', 'action', 'counter'])
                ->make(true);
        }  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manager.prospects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if(getManagerId() === null && Auth::user()->role_id != 1) {
            return Redirect::back()->withErrors("You do not have the necessary rights to do this.");
        }
        $data = $request->validate([
            'name' => 'required|max:255',
            'country' => 'required|max:255',
            'email' => 'email:rfc|required|max:255',
            'callingCodeForm' => 'required|max:8',
            'phone' => 'required|max:255',
            'type' => 'required|max:255',
        ]);
        $prospect = new Prospect;
        $prospect->name = $request->name;
        $prospect->country = $request->country;
        $prospect->email = $request->email;
        $prospect->phone = $request->callingCodeForm.$request->phone;
        $prospect->type = $request->type;
        $prospect->actor = Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["id"];
        $prospect->state = 2;
        // Automatically booked for 3 months
        $prospect->deadline = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")+3, date("d"), date("Y")));
        $prospect->creator = getManagerId();
        $prospect->save();
        
        $trackings = collect();
        $offers = collect();
        $comments = collect();
        return view('manager.prospects.show', compact(['prospect', 'trackings', 'offers', 'comments']))->with('created', "The prospect has been created!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Prospect $prospect)
    {
        $trackings = Tracking::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        $offers = Offer::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        $comments = ProspectComments::all()->where('prospect_id', $prospect->id)->sortByDesc('created_at');
        return view('manager.prospects.show', compact(['prospect', 'trackings', 'offers', 'comments']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Prospect $prospect)
    {
        return view('manager.prospects.edit', compact('prospect'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prospect $prospect)
    {
        if(getManagerId() != $prospect->creator && Auth::user()->role_id != 1) {
            return Redirect::back()->withErrors("You do not have the necessary rights to do this.");
        }
        $data = $request->validate([
            'name' => 'required|max:255',
            'country' => 'required|max:255',
            'email' => 'email:rfc|required|max:255',
            'callingCodeForm' => 'required|max:8',
            'phone' => 'required|max:255',            
            'type' => 'required|max:255',
        ]);
        $prospect->name = $request->name;
        $prospect->country = $request->country;
        $prospect->email = $request->email;
        $prospect->phone = $request->callingCodeForm.$request->phone;
        $prospect->type = $request->type;
        $prospect->save();
        
        $trackings = Tracking::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        $offers = Offer::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        $comments = ProspectComments::all()->where('prospect_id', $prospect->id)->sortByDesc('created_at');
        return view('manager.prospects.show', compact(['prospect', 'trackings', 'offers', 'comments']))->with('created', "The prospect has been updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prospect $prospect)
    {
        if(Auth::user()->role_id != 1) {
            return Redirect::back()->withErrors("You do not have the necessary rights to do this.");
        }
        $prospect->delete();
        return redirect('manager/prospects')->with('deleted', 'This prospect has been deleted.');            
    }

    /**
    * Show the form for the booking of the prospect.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function formBooking(Prospect $prospect)
    {
        return view('manager.prospects.booking', compact('prospect'));
    }

    /**
    * Update the prospect with the booking infos.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function book($id)
    {
        $prospect = Prospect::findOrFail($id);        
        
        
        if(getManagerType() === "TM" && $prospect->type === "Carrier" || getManagerType() === "LM" && $prospect->type === "Client") {
            return Redirect::back()->withErrors("You do not have the necessary rights to do this.");
        } else if($prospect->state != 1 && Auth::user()->role_id != 1) {
            return Redirect::back()->withErrors("You do not have the necessary rights to do this.");
        }
        
        $prospect->actor = getManagerId();
        $prospect->state = 2;
        $prospect->deadline = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m")+3, date("d"), date("Y")));
        $prospect->save();

        $trackings = Tracking::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        $offers = Offer::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        $comments = ProspectComments::all()->where('prospect_id', $prospect->id)->sortByDesc('created_at');
        return view('manager.prospects.show', compact(['prospect', 'trackings', 'offers', 'comments']))->with('booked', "The prospect has been booked!");
    }

}
