<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Prospect;
use App\Models\ProspectComments;
use App\Models\Tracking;
use Illuminate\Http\Request; 

class ProspectCommentsController extends Controller
{
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('manager.prospects.comments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(getManagerId() === null && Auth::user()->role_id != 1) {
            return Redirect::back()->withErrors("You do not have the necessary rights to do this.");
        }
        $data = $request->validate([
            'id_prospect' => 'required',
            'comment' => 'required|max:191'
        ]);
        $prospect = Prospect::findOrFail($request->id_prospect);
        $comment = new ProspectComments();
        $comment->prospect_id = $prospect->id;
        $comment->author = getManagerId();
        $comment->comment = $request->comment;
        $comment->save();        

        $trackings = Tracking::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        $offers = Offer::all()->where('id_prospect', $prospect->id)->sortByDesc('created_at');
        $comments = ProspectComments::all()->where('prospect_id', $prospect->id)->sortByDesc('created_at');
        return view('manager.prospects.show', compact(['prospect', 'trackings', 'offers', 'comments']))->with('comment_created', "The prospect comment has been added!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = ProspectComments::findOrFail($id);
        return view('manager.prospects.comments.edit', compact('comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProspectComments $comment)
    {
        if(getManagerId() != $comment->author) {
            return Redirect::back()->withErrors("You do not have the necessary rights to do this.");
        }
        $data = $request->validate([
            'comment' => 'required|max:191'
        ]);
        $prospect = Prospect::findOrFail($comment->prospect_id);
        $comment->comment = $request->comment;
        $comment->save();        

        $trackings = Tracking::all()->where('id_prospect', $comment->prospect_id)->sortByDesc('created_at');
        $offers = Offer::all()->where('id_prospect', $comment->prospect_id)->sortByDesc('created_at');
        $comments = ProspectComments::all()->where('prospect_id', $comment->prospect_id)->sortByDesc('created_at');
        return view('manager.prospects.show', compact(['prospect', 'trackings', 'offers', 'comments']))->with('comment_edited', "The prospect comment has been edited!");
    }

}
