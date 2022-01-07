<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use App\Models\Partner;
use Auth;
use Yajra\DataTables\DataTables;

class PartnerController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    **/
    public function index()
    {
        return view('manager.partners.index');
    }

    /**
    * Generate a yajara data table of the groups.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return Yajra\DataTables\DataTables
    **/
    public function getPartners(Request $request)
    {
        if ($request->ajax()) {      
            if (Auth::user()->role_id === 3) {
                $manager_id = Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["id"];
                $data = Partner::all()->where("manager_id", "=", $manager_id);
            }            
            return DataTables::of($data)                
                ->addIndexColumn()
                ->removeColumn('status', 'type')                                
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
                ->rawColumns(['showBtn'])                
                ->make(true);
        }  
    }

    /**
    * Display the specified resource.
    *
    * @param  $id
    * @return \Illuminate\Http\Response
    **/
    public function show($id)
    {
        try {
            $partner = Partner::findOrFail($id);            
            $updated_at = ($partner->created_at == $partner->updated_at) ? "none" : $partner->updated_at->format('d-m-Y H:i');
            return json_encode(
                array(
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
}
