<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\horaire;
use App\Models\Agency;



class HoraireController extends Controller
{
    public function index()
    {
        $horaires = horaire::with('agency')
                    ->where('agency_id','=','1')
                    ->where('manager_type','=','LM')
                    ->get();
        $agencies = Agency::get();

        return view('admin.horaires.index')
            ->with("horaires",$horaires)
            ->with("agencies",$agencies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $horaire = new horaire;
        $horaire->horaire_text = $request->hour_add_hour;
        $horaire->manager_type = $request->manager_add_hour;
        $horaire->agency_id  = $request->agency_add_hour;
        $horaire->save();
        return redirect()->route('admin.horaires.index')->with('succesadd','the Hour has been added successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(Partner $partner)
    {
        //
    }
    public function searchhour(Request $request)
    {

        return $request;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partner $partner)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        
    }
}
