<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manager\Group;
use App\Models\Partner;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'Group Management';
        // $groups = Group::all();
        // $groups = Group::orderBy('id','asc')->paginate(10);
        try {
            $search = $request->get('searchbar');
            // $cities = City::whereNotNull('city_name')->where('city_name','like','%'.$search.'%')->orderBy('city_name')->paginate(10);    
            $groups = Group::where('groupName','like','%'.$search.'%')->orderBy('id','asc')->paginate(10);
        } catch (\Throwable $th) {
            // $cities = City::whereNotNull('city_name')->orderBy('city_name')->paginate(10);
            $groups = Group::orderBy('id','asc'
            )->paginate(10);
        }  
        return view('manager.groups.index')->with('groups', $groups);
        // return view('manager.groups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $groups = Group::get();
        $partners = Partner::where('type', '=', 'Client')
                            ->where('status', '=', 1)
                            ->get();
        return view('manager.groups.create')->with('partners',$partners)->with('groups',$groups);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $group = new Group;
        $group->groupName = $request->input('groupName');

        $group->save();
        return redirect('groups');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $group = Group::find($id);
        // var_dump($group->partners);
        // return view('manager.groups.index')->with('group', $group);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $partners = Partner::where('type', '=', 'Client')
                            ->where('status', '=', 1)
                            ->get();
        $title = 'Update Group';
        $group = Group::find($id);
        $partnersSelected = $group -> partners;
        $partners_gr = array();
        foreach ($partnersSelected as $row){
            $partners_gr[] = $row->id;
        }
        return view('manager.groups.edit')->with('group',$group)->with('title',$title)
                                ->with('partners_gr',$partners_gr)
                                ->with('partners',$partners);
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
        $group = Group::find($id);
        $group->groupName = $request->groupName;
        $group->save();
        // $group->update($request->input('groupName'));
        $groupId = $group->id;
            $group1 = Group::find($groupId);
            if(!$group1)
                return abort('404');    
        $group1 -> partners()->sync($request -> partnersId);    
        return redirect()->route('manager.groups.index')
                        ->with('success','Group updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
