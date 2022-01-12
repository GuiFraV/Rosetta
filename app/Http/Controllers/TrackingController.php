<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use App\Models\Tracking;
use App\Models\Prospect;

class TrackingController extends Controller
{
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manager.prospects.trackings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $prospect = Prospect::findOrFail($request->id);   
        if(getManagerId() != $prospect->actor && Auth::user()->role_id != 1) {
            return Redirect::back()->withErrors("You do not have the necessary rights to do this.");
        }  
        if ($request->result === '0') {
            $data = $request->validate([
                'id' => 'required',
                'result' => 'required',
                'comment' => 'required|max:255'
            ]);                
            
            $tracking = new Tracking;
            $tracking->id_prospect = $prospect->id;
            $tracking->actor = isset($prospect->actor) ? $prospect->actor : getManagerId();
            $tracking->comment = $request->comment;
            $tracking->save();

            $prospect->actor = null;
            $prospect->state = 1;
            $prospect->deadline = null;
            // Actually set on 6 months, but modify to change the time necessary to wait before new booking
            $prospect->unavailable_until = date("Y-m-d H:i:s", mktime(0, 0, 0, date('n')+6, 1, date('y')));
            $prospect->save();            
            return redirect('manager/prospects')->with('archived', 'The archive of this prospect has been done.');            
        } else if ($request->result === '1') {            
            $data = $request->validate([
                'id' => 'required',
                'result' => 'required',
                'loadNumber' => 'required|max:255',
                'contact' => 'required|max:191'
            ]);            
            $prospect->deadline = null;
            if(!isset($prospect->actor))
                $prospect->actor = getManagerId();
            $prospect->unavailable_until = null;
            $prospect->state = 4;
            $prospect->loadNumber = $request->loadNumber;
            $prospect->save();
            // ATM not deleting the old prospect, but we will see in the future
            // Need to send email to manager with the load number to verify it, and not lose the information

            // Transforming the prospect into a new partner 
            $partner = new Partner();
            $partner->manager_id = $prospect->actor;
            $partner->name = $request->contact; 
            $partner->company = $prospect->name;
            $partner->origin = $prospect->country;
            $partner->phone = $prospect->phone;
            $partner->email = $prospect->email;
            $partner->type = $prospect->type;
            $partner->save();
            // 

            return redirect('manager/prospects')->with('validated', "The prospect has been validated, good work!"); 
        } 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tracking $tracking)
    {
        return view('manager.prospects.trackings.edit', compact('tracking'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tracking $tracking)
    {
        if(getManagerId() != $tracking->actor) {
            return Redirect::back()->withErrors("You do not have the necessary rights to do this.");
        }
        $data = $request->validate([
            'comment' => 'required|max:255'
        ]);
        $tracking->comment = $request->comment;
        $tracking->save();
        return back()->with('message', "The edit of this prospect history has been done!");       
    }

}
