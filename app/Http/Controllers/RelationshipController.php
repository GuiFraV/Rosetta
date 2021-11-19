<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manager\Group;
use App\Models\Partner;

class RelationshipController extends Controller
{
    // public function showPartner($partner_id)
    // {
    //     // $partner = Partner::where('id', '=', $partner_id)->get();
    //     $partner = Partner::with('groups')->find($partner_id);
    //     return $partner -> groups;
    //     // return $partner -> name;
    //     return view('groups.showPartner');
    // }
    public function showGroup($group_id)
    {
        // $partner = Partner::where('id', '=', $group_id)->get();
        return $partner = Group::with(['partners'=> function($q){
            $q -> select('id','name');
        }])->find($group_id);
        // return $partner -> groups;
        // // return $partner -> name;
        // return view('groups.showPartner');
    }
    public function showPartner($group_id)
        {
            
            $group = Group::find($group_id);
            $title = 'Clients of : '.$group->groupName;
            $partners = $group -> partners;
            return view('groups.showPartner')->with('partners',$partners)->with('title',$title);
        }
    public function savePartnerToGroup(Request $request)
        {
            // $title = 'Group Management';
            $group = new Group;
            $group->groupName = $request->input('groupName');
            $group->save();

            $groupId = $group->id;
            $group1 = Group::find($groupId);
            if(!$group1)
                return abort('404');    
            // return $request;
            
            // // $group -> partners()->attach($request -> partnersId);                        // adding partners to group
            // // $group -> partners()->sync($request -> partnersId);                          // updating partners in group and deleting old partners
               $group1 -> partners()->syncWithoutDetaching($request -> partnersId);          // adding partners to group without deleting old partners
               return redirect()->route('manager.groups.index');
   
        }
        public function deletePartnerFromGroup($group_id,$partner_id)
        {
            // $group_id = $request->input('group_id');
            //$group = Group::find(json_decode($partner)["pivot"]["group_id"]);
            //$group->partners()->detach($partner->id);
            $group = Group::find($group_id);
            $group->partners()->detach($partner_id);
            return redirect()->back();
        }
}
