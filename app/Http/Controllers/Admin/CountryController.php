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
        $activated = Country::where("isActive", "=", "1")->orderBy('id', 'asc')->get();        
        $disabled = Country::where("isActive", "=", "0")->orderBy('id', 'asc')->get();

        if(empty($activated) && empty($disabled))
          echo json_encode([
            "statusCode" => 400  
          ]);
      
        $arrOptions = array();

        foreach($activated as $act) 
          array_push($arrOptions, "<option value='".$act['id']."' selected>".$act['emoji']." ".$act['shortname']." (".$act['code'].")"."</option>");

        foreach($disabled as $dis)
          array_push($arrOptions, "<option value='".$dis['id']."'>".$dis['emoji']." ".$dis['shortname']." (".$dis['code'].")"."</option>");

        echo json_encode([
          "statusCode" => 200,
          "options" => $arrOptions
        ]);
    }
}
