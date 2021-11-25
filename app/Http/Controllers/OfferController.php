<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Manager;
use App\Models\Prospect;
use App\Models\Tracking;

class OfferController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manager.prospects.offers.create');
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
            'id_prospect' => 'required',                  
            'cityFrom' => 'string|required|max:255',
            'cityTo' => 'string|required|max:255',
            'offer' => 'numeric|required',
            'comment' => 'max:255'
        ]);
        $offer = new Offer;
        $offer->id_prospect = $request->id_prospect;
        $offer->actor = Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["id"];
        $offer->cityFrom = $request->cityFrom;
        $offer->cityTo = $request->cityTo;
        $offer->offer = $request->offer;
        $offer->comment = $request->comment;
        $offer->save();

        $prospect = Prospect::findOrFail($request->id_prospect);
        $trackings = Tracking::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        $offers = Offer::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        return view('manager.prospects.show', compact(['prospect', 'trackings', 'offers']))->with('offer_created', "The prospect's offer has been created!");
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
    public function edit(Offer $offer)
    {
        return view('manager.prospects.offers.edit', compact('offer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offer $offer)
    {
        $data = $request->validate([
            'cityFrom' => 'string|required|max:255',
            'cityTo' => 'string|required|max:255',
            'offer' => 'numeric|required',
            'comment' => 'max:255'
        ]);
        $offer->cityFrom = $request->cityFrom;
        $offer->cityTo = $request->cityTo;
        $offer->offer = $request->offer;
        $offer->comment = $request->comment;
        $offer->save();

        $prospect = Prospect::findOrFail($offer->id_prospect);
        $trackings = Tracking::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        $offers = Offer::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        return view('manager.prospects.show', compact(['prospect', 'trackings', 'offers']))->with('offer_edited', "The prospect's offer has been edited!");
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
