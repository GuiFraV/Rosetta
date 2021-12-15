<?php

namespace App\Http\Controllers;

use App\Models\MarketingSearch;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MarketingSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('manager.prospects.marketingsearches.index');
    }

    public function getMarketingSearches(Request $request)
    {
        if ($request->ajax()) {
            
            $data = MarketingSearch::latest()->where('creator', '=', getManagerId())->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('country', function($row)
                {
                    return countryCodeToEmojiName($row->country);
                })
                ->editColumn('email', function($row)
                {
                    $actionBtn = '<a role="button" class="bi bi-clipboard" style="font-size: 1.3rem; white-space: nowrap;" onclick="let ref = getElementById(\'mailCopy\'); ref.value = \''.$row->email.'\'; ref.style.display=\'block\'; ref.select(); document.execCommand(\'copy\'); ref.style.display=\'none\'; ref.value =\'\';"></a>';
                    return $actionBtn . ' ' . $row->email;
                })
                ->editColumn('created_at', function($row)
                {
                    $created_at = $row->created_at->format('Y-m-d');
                    return $created_at;
                })
                ->addColumn('editBtn', function($row)
                {
                    $editBtn = '<div class="d-flex justify-content-center align-items-center"><a href="marketingsearch/'.$row->id.'/edit" role="button" class="bi bi-pencil" style="font-size: 1.8rem;"></a></div>';
                    return $editBtn;
                })
                ->addColumn('transformBtn', function($row)
                {
                    $transformBtn = '<div class="d-flex justify-content-center align-items-center"><a href="transform/'.$row->id.'" role="button" class="bi bi-check2" style="font-size: 1.8rem;"></a></div>';
                    return $transformBtn;
                })
                ->addColumn('deleteBtn', function($row)
                {
                    $deleteBtn = '<form id="destroy'.$row->id.'" action="'. route('manager.marketingsearch.destroy', $row->id) .'" method="POST">
                                    <input type="hidden" name="_token" value="'.csrf_token().'">
                                    <input type="hidden" name="_method" value="delete">                 
                                    <a role="button" class="bi bi-trash" style="font-size: 1.8rem;" onclick="event.preventDefault(); this.closest(\'form\').submit();"></a>
                                </form>';
                    return $deleteBtn;
                }) 
                ->rawColumns(['email', 'editBtn', 'transformBtn', 'deleteBtn'])
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
        return view('manager.prospects.marketingsearches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'country' => 'required|max:255',
            'email' => 'email:rfc|required|max:255',
            'callingCodeForm' => 'required|max:8',
            'phone' => 'required|max:255',
            'type' => 'required|max:255',
        ]);
        $marketingSearch = new MarketingSearch;
        $marketingSearch->name = $request->name;
        $marketingSearch->country = $request->country;
        $marketingSearch->email = $request->email;
        $marketingSearch->phone = $request->callingCodeForm.$request->phone;
        $marketingSearch->type = $request->type;
        $marketingSearch->creator = getManagerId();
        $marketingSearch->save();
        return redirect('manager/prospect/marketingsearch')->with('created', "The marketing search has been created!");            
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $marketingSearch = MarketingSearch::findOrFail($id);
        return view('manager.prospects.marketingsearches.edit', compact('marketingSearch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marketingSearch = MarketingSearch::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|max:255',
            'country' => 'required|max:255',
            'email' => 'email:rfc|required|max:255',
            'callingCodeForm' => 'required|max:8',
            'phone' => 'required|max:255',
            'type' => 'required|max:255',
        ]);
        $marketingSearch->name = $request->name;
        $marketingSearch->country = $request->country;
        $marketingSearch->email = $request->email;
        $marketingSearch->phone = $request->callingCodeForm.$request->phone;
        $marketingSearch->type = $request->type;
        $marketingSearch->save();
        return redirect('manager/prospect/marketingsearch')->with('edited', "The marketing search has been updated!");            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marketingSearch = MarketingSearch::findOrFail($id);
        if(getManagerId() != $marketingSearch->creator) {
            return Redirect::back()->withErrors("You do not have the necessary rights to do this.");
        }
        $marketingSearch->delete();
        return redirect('manager/prospect/marketingsearch')->with('deleted', "The marketing search has been deleted!");                        
    }

    public function transform($id) {
        // dd($id);
        $marketingSearch = MarketingSearch::findOrFail($id);
        return view('manager.prospects.marketingsearches.create_prospect', compact(['marketingSearch']));        
    }

}
