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
        $groups = Group::where('creator','=',getManagerId())->get();
        return view('manager.mails.index')->with("groups",$groups); //->with('title',$title)->with('mails', $mails)->with('lastMail',$lastMail)->with('groups',$groups);
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
                ->addColumn('sendBtn', function($row)
                {
                    $sendBtn = '<a role="button" class="bi bi-envelope text-primary" style="font-size: 1.4rem;" onclick="openSendModal('.$row->id.');"></a>';
                    return $sendBtn;
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
                ->rawColumns(['autoSend', 'sendBtn' , 'showBtn', 'editBtn', 'deleteBtn'])
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
    }

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

    //Request $request
    public function sendMail(Request $request) {
        
             
        $mailId = $request->input('mailId1');
        $groupId = $request->input('group_id');
        $sendDate = $request->input('sendDate');
        $newDate = date('D, d M Y H:i:s O', strtotime($sendDate));

         
        $mail = Mail::find($mailId);
        $group = Group::find($groupId);

        $details = [
            'object' => $mail->object,
            'message' => $mail->message
        ];

        $selectedPartners = $group -> partners;
        $partnersGroup = array();
        foreach ($selectedPartners as $partner){
            $partnersGroup[] = $partner->email;
        }
        /*
        */
        foreach ($partnersGroup as $partner){
            $client = new Client();
            // $res = $client->request('POST', 'https://api.mailgun.net/v3/sandboxd905481280454e0cb56438aba176aa59.mailgun.org/messages', 
            $res = $client->request('POST', 'https://api.mailgun.net/v3/sandbox9523439c43cd469fab938c565c1f8b33.mailgun.org/messages', 
            [
                'form_params' => 
                [
                    "from" => getManagerEmail(),#manager_email
                    "to" => $partner,
                    "subject" => $mail->object,
                    "text"=> $mail->message
                    // "o:deliverytime" => Carbon::now()->hours(2)->toRfc2822String(),
                    // "o:deliverytime" => $newDate,
                    // "html" => view('mails\myTestMail',compact('details'))->render()
                ],
                'auth' => 
                [
                    'api', 
                    'key-c02e140e0e1bcb64d3b94bc90876e02d'
                ]
            ]);
            $results = json_decode($res->getBody(), true);

        }
        
        if($results) {
            return json_encode("Success! Your E-mail has been sent.");
        } else {
            return json_encode("Failed! Your E-mail has not sent.");
        }  
    }
}
