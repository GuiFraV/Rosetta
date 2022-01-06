<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('manager.partners.index');
    }

    /**
    * Generate a yajara data table of the groups.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return Yajra\DataTables\DataTables
    */
    public function getPartners(Request $request)
    {
        if ($request->ajax()) {      
            $data = Partner::all();
            return DataTables::of($data)                
                ->addIndexColumn()
                ->removeColumn('status')                                
                ->editColumn('manager_id', function($row)
                {
                   $manager = getManagerName($row->manager_id, 'all');
                   return $manager;
                })
                ->editColumn('created_at', function($row)
                {
                   $created_at = $row->created_at->format('d-m-Y');
                   return $created_at;
                })
                ->addColumn('showBtn', function($row)
                {
                    $showBtn = '<a role="button" class="bi bi-eye text-primary" style="font-size: 1.4rem;" onclick="openShowModal('.$row->id.');"></a>';
                    return $showBtn;
                })                
                ->addColumn('editBtn', function($row)
                {
                    $editBtn = '<a role="button" class="bi bi-pencil text-warning" style="font-size: 1.4rem;" onclick="openModalEdit('.$row->id.');"></a>';
                    return $editBtn;
                })                
                ->addColumn('deleteBtn', function($row)
                {
                    $deleteBtn = '<a role="button" class="bi bi-trash text-danger" style="font-size: 1.4rem;" onclick="$(\'#destroyModal\').modal(\'show\'); $(\'#destroyedId\').val('.$row->id.');"></a>';
                    return $deleteBtn;
                })                                
                ->rawColumns(['showBtn', 'editBtn', 'deleteBtn'])                
                ->make(true);
        }  
    }

    /**
     * Take data related to a country and returns a set of label / values as proposition for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function countryAuto(Request $request)
    {
      // Look for countries (full/short name, and country code) containing the search term
      $term = strtolower($request->term);
      $res = DB::table('countries')->whereRaw("LOWER(fullname) LIKE '%".$term."%' OR LOWER(shortname) LIKE '%".$term."%' OR LOWER(code) LIKE '%".$term."%'")->get();
      
      // Put the results in an array containing 3 keys : label (displayed in the completion), value (the country code), and phone_code
      $retArr = array();
      foreach($res as $country) {
          array_push($retArr, ["label" => $country->emoji . " " . $country->shortname, "value" => $country->code, "phone_code" => $country->phone_code]);
      }
      
      return json_encode($retArr);
    }

    /**
    * Take data related to a manager and returns a set of label / values as proposition for the user.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function managerAuto(Request $request)
    {
      // Select managers (name, and agency) containing the search term and type
      $term = strtolower($request->term);
      $type = ($request->type == "Client") ? "TM" : "LM";

      /// Query en SQL : SELECT managers.id, first_name, last_name, agency_name FROM managers INNER JOIN agencies ON managers.agency_id = agencies.id WHERE (first_name LIKE '%TERM%' OR last_name LIKE '%TERM%') AND `type` = 'TYPE' 
      $res = DB::table('managers')
                ->join('agencies', 'managers.agency_id', '=', 'agencies.id')
                ->select('managers.id', 'first_name', 'last_name', 'agency_name')
                ->whereRaw("(LOWER(first_name) LIKE '%".$term."%' OR LOWER(last_name) LIKE '%".$term."%') AND `type` = '".$type."'")                  
                ->get();
   
      // Put the results in an array containing 2 keys : label (displayed for the completion), and value (the manager id)
      $retArr = array();
      foreach($res as $manager) {
          array_push($retArr, ["label" => $manager->first_name . " " . $manager->last_name . " | " . $manager->agency_name, "value" => $manager->id]);
      }
      return json_encode($retArr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /// Back validation
        $rules = [
            'company' => 'required',
            'contact' => 'required',
            'country' => 'required',                       
            'callingCodeForm' => 'required',          
            'phone' => 'required',
            'email' => 'required',            
            'type' => 'required',
            'manager' => 'required'
        ];
        
        // Custom errors to display them as a toast in JS
        $customError = [
            'company.required' => "The partner's company name is required.",
            'contact.required' => "The partner's contact name is required.",
            'country.required' => "The partner's origin is required.",            
            'callingCodeForm.required' => "The partner's phone is incorrect.",          
            'phone.required' => "The partner's phone is required.",
            'email.required' => "The partner's email is required.",            
            'type.required' => "The partner's type is required.",
            'manager.required' => "The partner's manager is required."
        ];

        $this->validate($request, $rules, $customError);
        
        /// Creating the new partner with the freshly validated data
        $partner = new Partner;
        $partner->manager_id = $request->manager;
        $partner->name = $request->contact;
        $partner->company = $request->company;
        $partner->origin = $request->country;        
        $partner->phone = $request->phone.$request->callingCodeForm;
        $partner->email = $request->email;
        $partner->type = $request->type;
        $status = $partner->save();
        
        /// Verifying the status of the save. The boolean '$status' tells it.
        if ($status) {
            echo json_encode(["statusCode" => 200]);
        } else {
            echo json_encode(["statusCode" => 400]);
        }       
    }

    /**
    * Display the specified resource.
    *
    * @param  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        try {
            $partner = Partner::findOrFail($id);            
            $updated_at = ($partner->created_at == $partner->updated_at) ? "none" : $partner->updated_at->format('d-m-Y H:i');
            return json_encode(array(
                "statusCode" => 200,
                "manager" => getManagerName($partner->manager_id, 'all'), 
                "contact" => $partner->name, 
                "company" => $partner->company, 
                "origin" => countryToHuman($partner->origin), 
                "phone" => $partner->phone, 
                "email" => $partner->email, 
                "type" => $partner->type, 
                "created_at" => $partner->created_at->format('d-m-Y H:i'), 
                "updated_at" => $updated_at                
            )); 
        /// In the case where the partner is not found, the user is notified      
        } catch(ModelNotFoundException $e) {
            return json_encode(
                array(
                    "statusCode" => 400                    
                )
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Partner  $partner
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        try {
            $partner = Partner::findOrFail($id);                        
            $phone_code = "+".getPhoneCode($partner->origin);
            $real_phone = substr($partner->phone, strlen($phone_code));
            return json_encode(
                array(
                    "statusCode" => 200,     
                    "editedId" => $id,               
                    "managerLabel" => getManagerName($partner->manager_id, "complete"), 
                    "managerValue" => $partner->manager_id, 
                    "contact" => $partner->name, 
                    "company" => $partner->company, 
                    "originLabel" => countryToHuman($partner->origin), 
                    "originValue" => $partner->origin,
                    "phone_code" => $phone_code,
                    "phone" => $real_phone, 
                    "email" => $partner->email, 
                    "type" => $partner->type
                )
            );       
        /// In the case where the partner is not found, the user is notified
        } catch(ModelNotFoundException $e) {
            return json_encode(
                array(
                    "statusCode" => 400                    
                )
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {        
        /// Back validation
        $rules = [            
            'partnerEditCompany' => 'required',
            'partnerEditContact' => 'required',
            'partnerEditCountry' => 'required',
            'partnerEditPhone' => 'required',
            'partnerEditCallingCodeForm' => 'required',
            'partnerEditEmail' => 'required',
            'partnerEditType' => 'required',
            'partnerEditManager' => 'required'
        ];
      
        // Custom errors to display them as a toast in JS
        $customError = [
            'partnerEditCompany.required' => "The partner's company name is required.",
            'partnerEditContact.required' => "The partner's contact name is required.",
            'partnerEditCountry.required' => "The partner's origin is required.",                        
            'partnerEditPhone.required' => "The partner's phone is required.",
            'partnerEditCallingCodeForm.required' => "The partner's phone is incorrect.",          
            'partnerEditEmail.required' => "The partner's email is required.",
            'partnerEditType.required' => "The partner's type is required.",
            'partnerEditManager.required' => "The partner's manager is required."
        ];

        $this->validate($request, $rules, $customError);
        
        
        try {
            /// Update the partner with the freshly validated data    
            $partner = Partner::findOrFail($id);       
            $partner->manager_id = $request->partnerEditManager;
            $partner->name = $request->partnerEditContact;
            $partner->company = $request->partnerEditCompany;
            $partner->origin = $request->partnerEditCountry;        
            $partner->phone = $request->phone.$request->partnerEditCallingCodeForm;
            $partner->email = $request->partnerEditEmail;
            $partner->type = $request->partnerEditType;
            $status = $partner->save();
            
            /// Verifying the status of the save. The boolean '$status' tells it.
            if ($status) {
                echo json_encode(
                    array(
                        "statusCode" => 200
                    )
                );
            } else {
                /// In the case where the update failed, the user is notified
                echo json_encode(
                    array(
                        "statusCode" => 400, 
                        "error" => "updateError"
                    )
                );
            }
        /// In the case where the partner is not found, the user is notified
        } catch(ModelNotFoundException $e) {                
            return json_encode(
                array(
                    "statusCode" => 400,
                    "error" => $e
                )
            );        
        }  
    }

    /**
    * Delete the partner from the database.
    *
    * @param  Request $request
    * @return \Illuminate\Http\Response
    */
    public function destroyer(Request $request) 
    {
        $ret = Partner::destroy($request->id);
        /// Verifying the status of the deletion. The boolean '$ret' tells it.
        if($ret) {
            return json_encode(["statusCode" => 200]);  
        } else {
            return json_encode(["statusCode" => 400]);  
        }
        
    }
}
