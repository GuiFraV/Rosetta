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
    * Display the index of the group management.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view('manager.groups.index');
    }

    /**
    * Generate a yajara data table of the groups.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return Yajra\DataTables\DataTables
    */
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
    * Return the partners list of the current manager into the modal new group.
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
    * Store a newly created group in the database.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {

        $rules = [
            'groupName' => 'required',
            'partnersId' => 'required'          
        ];
          
        $customError = [
            'groupName.required' => 'The group name is required.',
            'partnersId.required' => 'You must select at least one partner for the group.'
        ];
  
        $this->validate($request, $rules, $customError);

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
          "statusCode" => 200
        ));
    }

    /**
    * Get the group data and returns it in JSON in order to display the specified resource into a modal.
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
                    "statusCode" => 400,
                    "error" => $e
                )
            );
        }
    }

    /**
    * Load the data of the group and loads it into the editing form.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
      try {
          $group = Group::findOrFail($id);          
          $groupPartnerIds = GroupPartner::all()->where('group_id', '=', $id);
          $arrayIds = array();
          foreach($groupPartnerIds as $groupPartnerId) {              
              array_push($arrayIds, $groupPartnerId->partner_id);              
          }
          $partners = Partner::all()->where('manager_id', '=', getManagerId());        
          $selectOptionsArray = array();
          foreach($partners as $partner) {
              array_push($selectOptionsArray, ["label" => $partner['company'] . " | " . $partner['type'] . " | " . $partner['origin'], "value" => $partner['id']]);
          }
          return json_encode(
              array(
                  "statusCode"=>200,
                  "editedId" => $id,
                  "groupName" => $group->groupName,
                  "id" => $arrayIds,                  
                  "partnersOptions" => $selectOptionsArray
              )
          );
      } catch(ModelNotFoundException $e) {                
          return json_encode(
              array(
                  "statusCode" => 400,
                  "error" => $e
              )
          );        
      }
    }

    /**
    * Update the group resource in the database.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {  

        $rules = [
            'editedId' => 'required',
            'groupName' => 'required',
            'partnersIdEdit' => 'required'
        ];

        $customError = [
            'editedId.required' => 'We encountered an unexpected error, please reload the page.',
            'groupName.required' => 'The group name is required.',
            'partnersId.required' => 'You must select at least one partner for the group.'
        ];

        $this->validate($request, $rules, $customError);


        try {
            $group = Group::findOrFail($id);       
            $group->groupName = $request->groupName;
            $group->save();
            GroupPartner::where('group_id', '=', $id)->delete();
            foreach ($request->partnersIdEdit as $id) {
                $rel = new GroupPartner;
                $rel->group_id = $group->id;
                $rel->partner_id = $id;
                $rel->save();
            }
            return json_encode(array(
                "statusCode" => 200
            ));
        } catch(ModelNotFoundException $e) {                
            return json_encode(
                array(
                    "statusCode" => 400,
                    "error" => $e
                )
            );        
        }
    }

    /**
    * Delete the group from the database.
    *
    * @param  Request $request
    * @return \Illuminate\Http\Response
    */
    public function destroyer(Request $request) 
    {
        $ret = Group::destroy($request->id);
        return json_encode(array(
            "statusCode" => 200,
            "destroyStatus" => $ret
        ));
    }
}
