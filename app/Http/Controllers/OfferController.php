<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Manager;
use App\Models\Prospect;
use Illuminate\Validation\Rule;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

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
        //dd($request);
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
        return back()->with('message', "The offer has been created!");
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
        return back()->with('message', "The edit of this prospect offer has been done!");
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
