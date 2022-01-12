<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function index()
    {
        return view('admin.country.index');
    }

    /// Takes all the countries of the database and returns two arrays : one with the actived and another with the disabled countries.
    public function getCountries() 
    { 
        $activated = DB::table("countries")->where("isActive", "=", "1")->get();
        $disabled = DB::table("countries")->where("isActive", "=", "0")->get();

        if(empty($activated) && empty($disabled))
          echo json_encode([
            "statusCode" => 400  
          ]);

        $arrOn = array();
        $arrOff = array();

        foreach($activated as $act) 
          array_push($arrOn, $act);
        

        foreach($disabled as $dis)
          array_push($arrOff, $dis);

        echo json_encode([
          "statusCode" => 200,
          "activated" => $arrOn,
          "disabled" => $arrOff
        ]);
    }
}
