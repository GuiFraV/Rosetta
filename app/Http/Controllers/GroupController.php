<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Partner;
use App\Models\GroupPartner;
use App\Models\Manager;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('manager.groups.index'); //->with('title',$title)->with('mails', $mails)->with('lastMail',$lastMail)->with('groups',$groups);
    }

    public function getGroups(Request $request)
    {
        if ($request->ajax()) {      
            $data = Group::all();
            return DataTables::of($data)                
                ->addIndexColumn()                
                ->editColumn('created_at', function($row)
                {
                   $created_at = $row->created_at->format('d-m-Y');
                   return $created_at;
                })
                ->editColumn('updated_at', function($row)
                {
                   $updated_at = $row->updated_at->format('d-m-Y');
                   return $updated_at;
                })
                ->addColumn('showBtn', function($row)
                {
                    $showBtn = '<a role="button" class="bi bi-eye text-primary" style="font-size: 1.4rem;" onclick="openShowModal('.$row->id.');"></a>';
                    return $showBtn;
                })
                ->addColumn('editBtn', function($row)
                {
                    $editBtn = '<a role="button" class="bi bi-pencil text-warning" style="font-size: 1.4rem;" onclick="openModalEdit('.$row->id.');"></a>';
                    return $editBtn;
                })
                ->addColumn('deleteBtn', function($row)
                {
                    $deleteBtn = '<a role="button" class="bi bi-trash text-danger" style="font-size: 1.4rem;" onclick="$(\'#destroyModal\').modal(\'show\'); $(\'#destroyedId\').val('.$row->id.');"></a>';
                    return $deleteBtn;
                })                
                ->rawColumns(['showBtn', 'editBtn', 'deleteBtn'])
                ->make(true);
        }  
    }

    /**
    * Return the partners list of the current manager to the modal new group.
    *
    * @return \Illuminate\Http\Response
    */
    public function openModalNew() {
        $partners = Partner::all()->where('manager_id', '=', getManagerId());        
        $responseArray = array();
        foreach($partners as $partner) {
            array_push($responseArray, ["label" => $partner['company'] . " | " . $partner['type'] . " | " . $partner['origin'], "value" => $partner['id']]);
        }
        return json_encode($responseArray);        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
          'groupName' => 'required',
          'partnersId' => 'required'
        ]);   
        
        $group = new Group;
        $group->groupName = $request->groupName;
        $group->creator = Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["id"];
        $group->save();
        
        foreach($request->partnersId as $id) {
            $rel = new GroupPartner;
            $rel->group_id = $group->id;
            $rel->partner_id = $id;
            $rel->save();
        }

        return json_encode(array(
          "statusCode"=>200
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $group = Group::findOrFail($id);
            $responseArray = array();
            $updated_at = ($group->created_at == $group->updated_at) ? "none" : $group->updated_at->format('d-m-Y H:i');
            array_push($responseArray, [
                "group" => [
                    "name" => $group->groupName, 
                    "creator" => getManagerName($group->creator, 'all'), 
                    "created_at" => $group->created_at->format('d-m-Y H:i'), 
                    "updated_at" => $updated_at
                ]
            ]);

            $groupPartnerIds = GroupPartner::all()->where('group_id', '=', $id);
            foreach($groupPartnerIds as $groupPartnerId) {
                $partner = Partner::find($groupPartnerId->partner_id);
                array_push($responseArray, [
                    "partner" => [
                        "company" => $partner['company'],
                        "origin" => $partner['origin'],
                        "phone" => $partner['phone'],
                        "email" => $partner['email']
                    ]
                ]);
            }
            array_push($responseArray, ["statusCode"=>200]);
            return json_encode($responseArray);       
        } catch(ModelNotFoundException $e) {
            return json_encode(
                array(
                    "statusCode"=>400,
                    "error"=>$e
                )
            );
        }

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
    * @param  Request $request
    * @return \Illuminate\Http\Response
    */
    public function destroyer(Request $request) 
    {
        $ret = Group::destroy($request->id);
        return json_encode(array(
            "statusCode"=>200,
            "destroyStatus"=>$ret
        ));
    }
}
