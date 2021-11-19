<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use App\Models\Partner;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $title = 'Partners Management';
        // $partners = Partner::all();
        // $partners = Partner::orderBy('created_at','desc')->paginate(10);
        try {
            $search = $request->get('searchbar');
            // $cities = City::whereNotNull('city_name')->where('city_name','like','%'.$search.'%')->orderBy('city_name')->paginate(10);    
            $partners = Partner::where('name','like','%'.$search.'%')
                                ->orderBy('created_at','desc')->paginate(10);
        } catch (\Throwable $th) {
            // $cities = City::whereNotNull('city_name')->orderBy('city_name')->paginate(10);
            $partners = Partner::orderBy('created_at','desc')->paginate(10);
        }  
        return view('admin.partners.index')->with('partners', $partners)->with('title',$title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $managers = Manager::all();
        return view('admin.partners.create')->with('managers',$managers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'manager_id' => 'required',
            'name' => 'required',
            'company' => 'required',
            'origin' => 'required',
            'phone' => 'required',
            'email' => 'required',
            // 'type' => 'required'
        ]);

        $partner = new Partner;

            if($request->type_partner == 'client'){

                $partner->type = 'Client';
                // return('Client');
            } 
            else
            {
                $partner->type = 'Carrier';
                // return('Carrier');
            } 

        // $partner->lastId = $request->input('lastId');
        $partner->manager_id = $request->input('manager_id');
        $partner->name = $request->input('name');
        $partner->company = $request->input('company');
        $partner->origin = $request->input('origin');
        $partner->phone = $request->input('phone');
        $partner->email  = $request->input('email');
        // $partner->type = $request->input('type');

        $partner->save();
        // return redirect('partners')->with('success','Epartner created');
        // return Redirect::back()->with('error_code', 5);
        
        // $request->except('_token');
        // $object=$request->get('object');
        // $message=$request->get('message');

        // $insertData=partner::insert(
        //     ['object' => $object , 'message' => $message]
        // );
        return redirect()->route('admin.partners.index')
                         ->with('success','Partner created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Update Partner';
        $managers = Manager::all();
        $partner = Partner::find($id);
        return view('admin.partners.edit')->with('partner',$partner)->with('title',$title)->with('managers',$managers);
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
        $this->validate($request, [
            'name' => 'required',
            'company' => 'required',
            'origin' => 'required',
            'phone' => 'required',
            'email' => 'required',
            // 'type' => 'required'
        ]);
        
        $partner = Partner::find($id);
        $partner->name = $request->input('name');
        $partner->company = $request->input('company');
        $partner->origin = $request->input('origin');
        $partner->phone = $request->input('phone');
        $partner->email  = $request->input('email');
        // $partner->type = $request->input('type');

        if($request->type_partner == 'client'){

            $partner->type = 'Client';
            // return('Client');
        } 
        else
        {
            $partner->type = 'Carrier';
            // return('Carrier');
        } 

        $partner->update();
        return redirect()->route('admin.partners.index')
                         ->with('success','Partner updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $partner = Partner::find($id);

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success','Partner deleted successfully');
    }
    public function partnerStatus($id)
    {
        $partner = Partner::find($id);
        $state = $partner->status;
        if ($state == 0) {
            $partner->status = 1;
        } else {
            $partner->status = 0;
        }
        $partner->update();
        return redirect()->route('admin.partners.index')
                         ->with('success','Partner updated successfully');
    }
}
