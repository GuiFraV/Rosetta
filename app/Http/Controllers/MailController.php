<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Models\Mail;
use App\Models\Group;
use App\Models\Manager;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail as MailG;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('manager.mails.index'); //->with('title',$title)->with('mails', $mails)->with('lastMail',$lastMail)->with('groups',$groups);
    }

    /**
    * New index function working with datatables.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function getMails(Request $request)
    {
        if ($request->ajax()) {      
            $data = Mail::all();
            return DataTables::of($data)                
                ->addIndexColumn()
                ->editColumn('autoSend', function($row)
                {
                    $autoSend = $row->autoSend;
                    
                    if($autoSend === 1) {
                        $ret = "<i class='bi bi-check2 text-success' style='font-size: 1.4rem;'></i>";
                    } else if ($autoSend === 0) {
                        $ret = "<i class='bi bi-x text-danger' style='font-size: 1.4rem;'></i>";
                    } else {
                        $ret = "Erreur";
                    }
                    return $ret;
                })
                ->addColumn('author', function($row) 
                {
                    $managerName = DB::table('managers')->select('first_name', 'last_name')->where('id', $row->author)->get();
                    foreach($managerName as $name)
                        return $name->first_name.' '.$name->last_name;
                })
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
                    $editBtn = '<a role="button" class="bi bi-pencil text-warning" style="font-size: 1.4rem;" onclick="openModalEditMail('.$row->id.');"></a>';
                    return $editBtn;
                })
                ->addColumn('deleteBtn', function($row)
                {
                    $deleteBtn = '<a role="button" class="bi bi-trash text-danger" style="font-size: 1.4rem;" onclick="$(\'#destroyModal\').modal(\'show\'); $(\'#destroyedId\').val('.$row->id.');"></a>';
                    return $deleteBtn;
                })                
                ->rawColumns(['autoSend', 'showBtn', 'editBtn', 'deleteBtn'])
                ->make(true);
        }  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* Make form validation when possible
        $request->validate([
            'object' => 'required',
            'message' => 'required'
        ]);
        */

        /*
        $mail = Mail::create([
            'object' => $request->object,
            'message' => $request->message,
            'author' => Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["id"]
        ]);
        */

        $mail = new Mail;
        $mail->object = $request->object;
        $mail->message = $request->message;
        $mail->author = Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["id"];
        $mail->save();

        return json_encode(array(
            "statusCode"=>200,
            "data"=>$mail
        ));
    }

    /**
     * Display the specified email in a modal.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        try {
            $mail = Mail::findOrFail($id);
            $autoDisplay = ($mail->autoSend == 1) ? "Yes" : "No";
            $updated_at = ($mail->created_at == $mail->updated_at) ? "none" : $mail->updated_at->format('d-m-Y H:i');
            return json_encode(array(
                "statusCode"=>200,
                "object"=>$mail->object,
                "message"=>$mail->message,
                "autoSend"=>$autoDisplay,
                "created_at"=>$mail->created_at->format('d-m-Y H:i'),
                "updated_at"=>$updated_at,
                "author" => getManagerName($mail->author, 'all')
            ));
        } catch(ModelNotFoundException $e) {
            return json_encode(
                array(
                    "statusCode"=>400,
                    "error"=>$e
                )
            );
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $mail = Mail::findOrFail($id);
            return json_encode(array(
                "statusCode"=>200,
                "object"=>$mail->object,
                "message"=>$mail->message,
                "autoSend"=>$mail->autoSend,
                "id"=>$id
            ));
        } catch(ModelNotFoundException $e) {
            return json_encode(
                array(
                    "statusCode"=>400,
                    "error"=>$e
                )
            );
        }
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
        $request->validate([
            'emailEditObject' => 'required',
            'emailEditContent' => 'required'
        ]);
        
        try {
            $mail = Mail::findOrFail($id);
            $mail->object = $request->emailEditObject;
            $mail->message = $request->emailEditContent;
            $mail->save();
            return json_encode(array(
                "statusCode"=>200
            ));
        } catch(ModelNotFoundException $e) {
            return json_encode(
                array(
                    "statusCode"=>400,
                    "error"=>$e
                )
            );
        }
        /*
        return json_encode(array(
            "statusCode"=>200,
            "data"=>$mail
        ));
        */
        /*
        $this->validate($request, [
             'object' => 'required',
             'message' => 'required'
         ]);
        $mail = Mail::find($id);
        $mail->object = $request->input('object');
        $mail->message = $request->input('message');
        $mail->save();
        $group->update($request->input('groupName'));
        return redirect()->route('groups.index')
                        ->with('success','Group updated successfully');
 
        $mail->update();
        return redirect('mails')->back()->with('success','Email updated');
        */
    }

    /*
    public function update(Request $request)
    {
        $dt = new DateTime();
        $mail_id = $request->input('mail_id');
        $mail->updated_at = $dt->format('Y-m-d H:i:s');
        $mail->update($request->all());
        return redirect()->route('mails.index')
                        ->with('success','Email updated successfully');

        $this->validate($request, [
            'object' => 'required',
            'message' => 'required'
        ]);
        $id = $request->input('id1');
        $mail = Mail::find($id);
        $mail->object = $request->input('object');
        $mail->message = $request->input('message');

        $mail->update();
        return redirect()->route('mails.index')
                         ->with('success','Email updated successfully');
    }
    */

    /**
    * Remove the specified resource from storage.
    *
    * @param  Request $request
    * @return \Illuminate\Http\Response
    */
    public function destroyer(Request $request) 
    {
        $ret = Mail::destroy($request->id);
        return json_encode(array(
            "statusCode"=>200,
            "destroyStatus"=>$ret
        ));
    }
}
