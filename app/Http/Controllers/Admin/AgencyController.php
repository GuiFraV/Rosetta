<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AgencyController extends Controller
{
    /**
     * Display a listing of the agencies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.agencies.index');
    }

    /**
    * Generate a Yajra data table of the agencies.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return Yajra\DataTables\DataTables
    */
    public function getAgencies(Request $request)
    {
        if ($request->ajax()) {
            $data = Agency::all();            
            return DataTables::of($data)                
                ->addIndexColumn()
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
                ->rawColumns(['editBtn', 'deleteBtn'])                
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
        // Back-End Validation        
        if(!$request->has('agencyNameNew')) {
            echo json_encode(array("error" => 1, "message" => "Server Error! Please reload the page and retry."));
        } else if(!$request->has('agencyAddressNew') || strlen($request->agencyAddressNew) > 512) {
            echo json_encode(array("error" => 1, "message" => "Server Error! Please reload the page and retry."));        
        } else if(!$request->has('agencyPhoneNew') || strlen($request->agencyPhoneNew) > 512) {
            echo json_encode(array("error" => 1, "message" => "Server Error! Please reload the page and retry."));        
        }

        $name = $request->agencyNameNew;
        $address = $request->agencyAddressNew;
        $phone = $request->agencyPhoneNew;

        $agency = new Agency;
        $agency->agency_name = $name;
        $agency->address = $address;
        $agency->office_phone = $phone;
        $status = $agency->save();

        if($status) {
            echo json_encode(array("error" => "0"));
        } else {
            echo json_encode(array("error" => "1", "message" => "Server Error! Please reload the page and retry."));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $agency = Agency::findOrFail($id);
        // try catch model not found exception
        echo json_encode(array("error" => 0, "agency" => ["id" => $agency->id, "name" => $agency->agency_name, "address" => $agency->address, "phone" => $agency->office_phone]));
        exit;
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

        if (!$request->has('editedId')) {
            return json_encode(array("error" => 1, "message" => "Server Error! Please reload the page and retry."));            
        } else if(!$request->has('agencyNameEdit')) {
            echo json_encode(array("error" => 1, "message" => "Server Error! Please reload the page and retry."));
        } else if(!$request->has('agencyAddressEdit') || strlen($request->agencyAddressEdit) > 512) {
            echo json_encode(array("error" => 1, "message" => "Server Error! Please reload the page and retry."));        
        } else if(!$request->has('agencyPhoneEdit') || strlen($request->agencyPhoneEdit) > 512) {
            echo json_encode(array("error" => 1, "message" => "Server Error! Please reload the page and retry."));        
        }
                
        $id = $request->editedId;
        $name = $request->agencyNameEdit;
        $address = $request->agencyAddressEdit;
        $phone = $request->agencyPhoneEdit;

        $agency = Agency::findOrFail($id);
        // try catch
        
        $agency->agency_name = $name;
        $agency->address = $address;
        $agency->office_phone = $phone;
        $status = $agency->save();

        if($status) {
            return json_encode(array("error" => "0"));
        } else {
            return json_encode(array("error" => "1", "message" => "Server Error! Please reload the page and retry."));
        }
        exit;
    }

    /**
     * Remove the agency from the database.
     *
     * @param  integer $id
     * @return String json
     */
    public function destroy($id)
    {
        $ret = Agency::destroy($id);
        // $ret : true -> deletion done, false -> error
        if($ret) {
            echo json_encode(["error" => 0]);  
        } else {
            echo json_encode(["error" => 1, "message" => "Server Error! Please reload the page and retry."]);  
        }
        exit;
    }
}
