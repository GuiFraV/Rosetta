<?php

namespace App\Http\Controllers;

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
        if ($request->result === '0') {
            $data = $request->validate([
                'id' => 'required',
                'result' => 'required',
                'comment' => 'required|max:255'
            ]);                
            $prospect = Prospect::findOrFail($request->id);
            $tracking = new Tracking;
            $tracking->id_prospect = $prospect->id;
            $tracking->actor = isset($prospect->actor) ? $prospect->actor : Auth::user()->id_manager;
            $tracking->comment = $request->comment;
            $tracking->save();

            $prospect->actor = null;
            $prospect->state = 1;
            $prospect->deadline = null;
            // Actually set on 6 months, but modify to change the time necessary to wait before new booking
            $prospect->unavailable_until = date("Y-m-d H:i:s", mktime(0, 0, 0, date('n')+6, 1, date('y')));
            $prospect->save();            
            return view('manager.prospects.index')->with('archived', 'The archive of this prospect has been done.');            
        } else if ($request->result === '1') {            
            $data = $request->validate([
                'id' => 'required',
                'result' => 'required',
                'loadNumber' => 'required|max:255'
            ]);            
            $prospect = Prospect::findOrFail($request->id);
            $prospect->deadline = null;
            if(!isset($prospect->actor))
                $prospect->actor = Auth::user()->id_manager;
            $prospect->unavailable_until = null;
            $prospect->state = 4;
            $prospect->loadNumber = $request->loadNumber;
            $prospect->save();
            return view('manager.prospects.index')->with('validated', "The prospect has been validated, good work!"); 
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
        $data = $request->validate([
            'comment' => 'required|max:255'
        ]);
        $tracking->comment = $request->comment;
        $tracking->save();
        return back()->with('message', "The edit of this prospect history has been done!");       
    }

}
