<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Models\Mail;
use App\Models\Group;
use App\Models\Manager;
use App\Models\Manager\Trajet as Trajet;
use App\Models\Partner;
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
            $data = Mail::where('author', '=', getManagerId())->get();
            return DataTables::of($data)                
                ->addIndexColumn()
                ->editColumn('autoSend', function($row)
                {
                    if($row->autoSend === 1) {
                        $ret = "<i class='bi bi-check2 text-success' style='font-size: 1.4rem;'></i>";
                    } else if ($row->autoSend === 0) {
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
                    $showBtn = '<a role="button" class="bi bi-eye text-primary" style="font-size: 1.4rem;" onclick="openShowEmailTemplateModal('.$row->id.');"></a>';
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

    public function sendMailTest(Request $request) {
    
        $complexMail = Mail::where("id", "=", "2")->first();

        $object = $complexMail->object;
        $message = $complexMail->message;

        $groupTest = array("mail.testing1@intergate-logistic.com", "mail.testing2@intergate-logistic.com", "developpement2@intergate-logistic.com");

        foreach ($groupTest as $currReceiver) {
            $client = new Client();
            $res = $client->request(
                'POST',
                'https://api.eu.mailgun.net/v3/form.loaditeasy.com/messages',
                [
                    'form_params' =>
                    [
                        "from" => getManagerEmail(),#manager_email
                        "to" => $currReceiver,
                        "subject" => $object,
                        "html" => $message
                    ],
                    'auth' => ['api', 'key-c02e140e0e1bcb64d3b94bc90876e02d']
                ]
            );
            $results = json_decode($res->getBody(), true);
        }
        return json_encode(array("error" => $results));        
    }

    /**
     * Send the email that have been prepared with MailGun.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendMail(Request $request) {
        
             
        $mailId = $request->emailSentId;
        $groupId = $request->selectSendToGroup;
        
        /*
        $sendDate = $request->input('sendDate');
        $newDate = date('D, d M Y H:i:s O', strtotime($sendDate));
        */
         
        $mail = Mail::findOrFail($mailId);
        $group = Group::findOrFail($groupId);

        $selectedPartners = $group->partners;
        
        $partnersGroup = array();
        foreach ($selectedPartners as $partner){
            $partnersGroup[] = $partner->email;
        }
        /*
        */
        foreach ($partnersGroup as $partner){
            $client = new Client();
            // $res = $client->request('POST', 'https://api.mailgun.net/v3/sandboxd905481280454e0cb56438aba176aa59.mailgun.org/messages', 
            $res = $client->request('POST', 'https://api.eu.mailgun.net/v3/form.loaditeasy.com/messages', 
            [
                'form_params' => 
                [
                    "from" => getManagerEmail(),#manager_email
                    "to" => $partner,
                    "subject" => $mail->object,
                    //"text"=> $mail->message
                    // "o:deliverytime" => Carbon::now()->hours(2)->toRfc2822String(),
                    // "o:deliverytime" => $newDate,
                    "html" => $mail->message
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

    /**
    * Send an email with given parameters with MailGun.
    *
    * @param  Request $request
    * @return \Illuminate\Http\Response
    */
    public function sendInstantMail(Request $request) {

        // Push all the partner's mail of the group in an array.
        if($request->selectRouteListGroup != "allMyContacts") {
            $selectedGroup = Group::findOrFail($request->selectRouteListGroup);
            $groupPartners = $selectedGroup->partners;
            $arrGroupPartners = array();
            foreach ($groupPartners as $partner)
                array_push($arrGroupPartners, $partner->email);    
        } else {
            $groupPartners = Partner::where("manager_id", "=", getManagerId())->get();
            $arrGroupPartners = array();
            foreach($groupPartners as $curr)
                array_push($arrGroupPartners, $curr->email);
        }

        $object = $request->emailRouteListObject;
        $message = $request->emailRouteListContent;

        foreach ($arrGroupPartners as $currReceiver) {
            $client = new Client();
            $res = $client->request(
                'POST',
                'https://api.eu.mailgun.net/v3/form.loaditeasy.com/messages',
                [
                    'form_params' =>
                    [
                        "from" => getManagerEmail(),#manager_email
                        "to" => $currReceiver,
                        "subject" => $object,
                        "html" => $message
                    ],
                    'auth' => ['api', 'key-c02e140e0e1bcb64d3b94bc90876e02d']
                ]
            );
            $results = json_decode($res->getBody(), true);
        }
        return json_encode(array("error" => $results));        
    }


    /**
     * Get the route List and returns it in a formatted way to insert it in a mail builder.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRouteList () {
        
        $typeManager = getManagerType();        
        
        $typeList = (getManagerType() === "LM") ? "Loads" : "Trucks";
        
        if($typeManager === "LM") {
            $dataFull = Trajet::where("visible", ">=", "0")->whereIn("zone_id", [1, 2, 3])->get();
            $dataPart = Trajet::where("visible", ">=", "0")->where("zone_id", "=", 4)->get();
        } else if($typeManager === "TM") {
            $dataFull = Trajet::where("visible", ">=", "0")->whereIn("zone_id", [5, 7])->get();
            $dataPart = Trajet::where("visible", ">=", "0")->where("zone_id", "=", 6)->get();
        } else if($typeManager === "Admin") {
            // No support for this type of user ATM
            $data = Trajet::where("visible", ">=", "0")->get();
        }

        if ($dataFull->isEmpty() && $dataPart->isEmpty()) { 
            $finalStr = "There are currently no loads or trucks available.";  
        }

        $retArrFull = array();        
        foreach($dataFull as $route) {            
            $firstSub = $route->from_others;
            
            // Check if the FROM is defined, else continue
            if($firstSub === null)
              continue;

            // If it's a load with multiple loading places, substr the first loading place (before '+')
            if(str_contains($firstSub, "+")) {
                $tmp = explode("+", $firstSub);
                $firstSub = $tmp[0];
            } 

            // Find the position of '(' and substr the right part
            $tmp = explode("(", $firstSub);   	
            $secondSub = $tmp[1];

            // Then Find the position of ')' and substr the left part
            $tmp = explode(")", $secondSub);

            $countryDeparture = $tmp[0];
            
            // Formating the text for the mail as a list
            $displayText = (isset($route->comment)) ? $route->from_others . " -> " . $route->to_others . " | " . $route->comment : $route->from_others . " -> " . $route->to_others;

            // Insert in an array the country code and the display text used for the email
            array_push($retArrFull, ["countryCode" => $countryDeparture, "label" => $displayText, "urgent" => $route->urgent, "isMatched" => $route->matched_to]);
        }

        $retArrPart = array();        
        foreach($dataPart as $route) {            
            $firstSub = $route->from_others;
            
            // Check if the FROM is defined, else continue
            if($firstSub === null)
              continue;

            // If it's a load with multiple loading places, substr the first loading place (before '+')
            if(str_contains($firstSub, "+")) {
                $tmp = explode("+", $firstSub);
                $firstSub = $tmp[0];
            } 

            // Find the position of '(' and substr the right part
            $tmp = explode("(", $firstSub);   	
            $secondSub = $tmp[1];

            // Then Find the position of ')' and substr the left part
            $tmp = explode(")", $secondSub);

            $countryDeparture = $tmp[0];
            
            // Formating the text for the mail as a list
            $displayText = (isset($route->comment)) ? $route->from_others . " -> " . $route->to_others . " | " . $route->comment : $route->from_others . " -> " . $route->to_others;            
            
            // Insert in an array the country code and the display text used for the email
            array_push($retArrPart, ["countryCode" => $countryDeparture, "label" => $displayText, "urgent" => $route->urgent, "isMatched" => $route->matched_to]);
        }
        
        // Ordering our return array in a ascendant way
        array_multisort($retArrFull, SORT_ASC);        
        array_multisort($retArrPart, SORT_ASC);        
        // dd($retArr);
        
        $finalStr = "<br><br><span style='font-weight: bold;'>FULL LOAD</span><br><br>";
        $tmp = "";
        foreach($retArrFull as $elem) {
            if($tmp != $elem['countryCode']) {
                $tmp = $elem['countryCode'];
                $finalStr .= "<br><br><span style='font-weight: bold;'>".$tmp."</span><br><br>";
            }
            $conditionnalCss = "";
            $conditionnalCss .= ($elem['urgent']) ? "color: red;" : "";
            $conditionnalCss .= ($elem['isMatched'] != null) ? " text-decoration-line: line-through;" : "";
            $finalStr .= "<span style='".$conditionnalCss."'>".$elem['label']."</span><br><br>";
        }
        $finalStr .= "<br><br><br><span style='font-weight: bold;'>PART LOAD</span><br><br>";
        foreach($retArrPart as $elem) {
            if($tmp != $elem['countryCode']) {
                $tmp = $elem['countryCode'];
                $finalStr .= "<br><br>".$tmp."<br><br>";
            }
            $conditionnalCss = "";
            $conditionnalCss .= ($elem['urgent']) ? "color: red;" : "";
            $conditionnalCss .= ($elem['isMatched'] != null) ? " text-decoration-line: line-through;" : "";
            $finalStr .= "<span style='".$conditionnalCss."'>".$elem['label']."</span><br><br>";
        }
    
        $insertManagerType = (getManagerType() === "LM") ? "Logistic Manager" : "Transport Manager";

        $signature = "
        <tr>
            <td bgcolor='#e1e4ec' style='padding: 20px 20px 20px 40px; border-bottom: 1px solid #989898;'>
                <span style='font-size: 28px;'>INTERGATE LOGISTIC</span><br />
                <span style='color: #6359ab; font-weight: bold;'> ". getManagerName(getManagerId(), 'all') ." - International ". $insertManagerType ."</span><br />
                <br>
                <table border='0' width='100%' style='text-align: left;'>
                    <tr>
                        <td style='font-weight: bold; width: 150px;'>Phone:</td>
                        <td>0033180855191</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>Mobile:</td>
                        <td>0033761910406</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>Web:</td>
                        <td><a href='www.intergate-logistic.com'>www.intergate-logistic.com</a></td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>Mail:</td>
                        <td><a href='mailto:transport2@intergate-logistic.com'>transport2@intergate-logistic.com</a></td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>Skype:</td>
                        
                        <td><a href='skype:intergate.logistic1?chat'>intergate.logistic1</a></td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>VAT:</td>
                        <td>FR95527908883</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>DUNS:</td>
                        <td>262558982</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>CMR Insurance:</td>
                        <td>1.000.000 €</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>Capital:</td>
                        <td>258.000 €</td>
                    </tr>
                    <tr>
                        <td colspan='2'>INTERGATE LOGISTIC - <span style='color: #6359ab; text-decoration: underline;'>149 route de Melun, 91250 Saintry sur Seine, France</span> - 91008 EVRY CEDEX France</td>
                    </tr>
                </table>
            </td>
        </tr>";

        $finalStr .= $signature;

        return json_encode(array("list" => $finalStr, "typeList" => $typeList, "todaysDate" => date('d-m-Y'), "error" => 0));
        exit;
    }
}
