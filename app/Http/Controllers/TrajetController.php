<?php

namespace App\Http\Controllers;

use App\Models\Manager\Trajet;
use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\City;
use App\Models\Manager;
use App\Models\Country;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Auth;
use DateTime;
use Illuminate\Support\Facades\DB;

class TrajetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $type_manager = getManagerType();        
        $zones = Zone::get(['zones.zone_name']);
        
        if(!empty($request->except('_token'))) {
            //code... || <- what did he mean by that ?
        
            $managerid = $request->get('managerid');
            if($managerid == 'test') {
                $type_search = "all";
                $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
                                ->where('trajets.visible', ">=", '0')
                                ->orderBy('trajets.date_depart', 'DESC')
                                // ->get(['trajets.id','trajets.date_depart','zones.zone_name','trajets.from_others','trajets.to_others','trajets.key','trajets.distance','trajets.duration','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars','trajets.manager_id']);
                                ->get(['trajets.id','trajets.date_depart','zones.zone_name', 'trajets.manager_id', 'trajets.from_others','trajets.to_others','trajets.distance','trajets.duration','trajets.key','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars', 'trajets.visible']);
            } else if($managerid != 'test') {
                $type_search = "bymanager";
                $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
                                ->where('trajets.manager_id','=',$managerid)
                                ->where('trajets.visible', ">=", '0')
                                ->orderBy('trajets.date_depart', 'DESC')
                                // ->get(['trajets.id','trajets.date_depart','zones.zone_name','trajets.from_others','trajets.to_others','trajets.key','trajets.distance','trajets.duration','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars','trajets.manager_id']);
                                ->get(['trajets.id','trajets.date_depart','zones.zone_name', 'trajets.manager_id', 'trajets.from_others','trajets.to_others','trajets.distance','trajets.duration','trajets.key','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars', 'trajets.visible']);
            }
        } else {
            //throw $th;
            $type_search = "all";
            $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
                            ->where('trajets.visible', ">=", '0')
                            ->orderBy('trajets.date_depart', 'DESC')
                            // ->get(['trajets.id','trajets.date_depart','zones.zone_name','trajets.from_others','trajets.to_others','trajets.key','trajets.distance','trajets.duration','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars','trajets.manager_id']);        
                            ->get(['trajets.id','trajets.date_depart','zones.zone_name', 'trajets.manager_id', 'trajets.from_others','trajets.to_others','trajets.distance','trajets.duration','trajets.key','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars', 'trajets.visible']);
        }
        return  view('manager.trajets.index', compact('zones') , compact('data') )->with('type_manager',$type_manager)->with('type_search',$type_search); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $zones = Zone::get();
        $countries  = Country::orderBy('fullname')->get();
        return view('manager.trajets.create')->with(compact('zones'))
                                            ->with(compact('request'))
                                            ->with(compact('countries'));
    }

    public function searchcity(Request $request) {
        if($request->ajax()) {          
            $data =City::join('countries', 'countries.id', '=', 'cities.coutry_id')
                        ->where('coutry_id', '=',$request->country_id)
                        ->orderBy('city_name')
                        ->get(['countries.country_code','cities.city_name']);                        
            return $data;                       
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
        // dd($request->all());
        
        // Var attribution for validation and insert
        $date_depart = $request->date_depart;
        $from_others = $request->from_cities;
        $to_others = $request->to_cities;
        
        $results = self::distancebtw(str_replace('+', '|', $from_others),str_replace('+', '|', $to_others));
        try {
            $distance = $results["rows"][0]["elements"][0]["distance"]["value"];
            $duration = $results["rows"][0]["elements"][0]["duration"]["value"];
        } catch (\Throwable $th) {
            $distance = "0";
            $duration = "0";
        }

        $key = ($request->key_radios === "key") ? 1 : 0;        
        $stars = $request->stars_select;
        $comment = $request->comment_trajet;
        
        $vehicles = $request->btnradio;
        $full_load = 0;
        
        if($vehicles === "on") {
          $vehicles = 0;
          $full_load = 1;
        }

        $used_cars = ($request->has('used_cars') && $request->used_cars === "checked") ? 1 : 0;        
        $intergateTruck = ($request->has('intergateTruck')) ? 1 : 0;        

        

        $tmpType = getManagerType();        
        $zone_id = 0;
        $arrZoneOne = array("EE", "LV", "LT", "CZ", "SK");
        $arrZoneTwo = array("DE", "AT", "IT", "BE", "NL", "LU", "DK", "SE", "NO");
        $arrZoneThree = array("FR", "ES", "PT", "GB", "CH");
        $countryCodeDeparture = $request->from_country_select1;
        if($tmpType === "TM") {
            if($full_load) {
                if(in_array($countryCodeDeparture, $arrZoneOne)) {
                    $zone_id = 1;
                } elseif(in_array($countryCodeDeparture, $arrZoneTwo)) {
                    $zone_id = 2;
                } elseif(in_array($countryCodeDeparture, $arrZoneThree)) {
                    $zone_id = 3;  
                }
            } else {
                $zone_id = 4;
            }
        } elseif($tmpType === "LM") {
            if($intergateTruck) {
                $zone_id = 7;
            } elseif($full_load) {
                $zone_id = 5;
            } else {
                $zone_id = 6;
            }
        }        

        // Not currently able to compare dates with this format
        // $tmp = new DateTime('yesterday');
        // $dateYesterdayCompare = $tmp->format('Y-m-d\TH:i:s');
        
        // Validation        
        if(
          $date_depart === null || 
          // $date_depart < $dateYesterdayCompare || 
          $zone_id === 0 || $zone_id === null || ($zone_id < 1 || $zone_id > 7) ||
          $key != 1 && $key != 2 ||
          $stars === null || ($stars != 1 && $stars != 2 && $stars != 3) ||
          $from_others === null || strlen($from_others) > 191 ||
          $to_others === null || strlen($to_others) > 191 ||
          $vehicles < 0 || $vehicles > 11
        ) {
          return redirect()->route('manager.trajets.index')->with('validationError','There has been an error during the creation, please retry.');
        }
        
        // dd($request);

        // Insertion
        $insertData = Trajet::insert([
            'date_depart' => $date_depart,
            'zone_id' => $zone_id,
            'manager_id' => getManagerId(),
            'from_others' => $from_others ,
            'to_others' => $to_others ,
            'distance' => $distance,
            'duration' => $duration,                
            'key' => $key,
            'stars' => $stars,                
            'comment' => $comment ,
            'vans' => $vehicles,
            'full_load' => $full_load,
            'used_cars' => $used_cars
        ]);
        // dd($insertData);
        return redirect()->route('manager.trajets.index')->with('created','The road has been created successfully.');        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Manager\Trajet  $trajet
     * @return \Illuminate\Http\Response
     */
    public function show(Trajet $trajet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Manager\Trajet  $trajet
     * @return \Illuminate\Http\Response
     */
    public function edit(Trajet $trajet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Manager\Trajet  $trajet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trajet $trajet)
    {
        //
    }

    /**
    * Update every Load / Truck that are visible for today (visible = 0), to visible for tommorrow (visible = 1).
    *
    * @return $countAffected, the number of rows that will be affected by this operation
    */
    public function duplicateAll() 
    {
        $countAffected = Trajet::where('visible', 0)->update(['visible' => 1]);
        echo json_encode($countAffected);
    }

    /**
    * Set visible at 0, so the Load / Truck won't be showed tommorrow.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return JSON
    */
    public function unduplicate(Request $request) 
    {        
        $trajet = Trajet::find($request->id);
        $trajet->visible = 0;        
        $trajet->save();
        
        $retType = "";
        switch($trajet->zone_id) {
            case 1:
            case 2:
            case 3:
            case 4:
                $retType = "load";
                break;
            case 5:
            case 6:
            case 7:
                $retType = "truck";
                break;
            default:
                $retType = "element";                
        }

        echo json_encode(array("error" => 0, "retType" => $retType));
    }

    /**
    * Set visible at 1, so the Load / Truck will be showed tommorrow.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return JSON
    */
    public function duplicate(Request $request) 
    {        
        $trajet = Trajet::find($request->id);
        $trajet->visible = 1;                
        $trajet->save();
        
        $retType = "";
        switch($trajet->zone_id) {
            case 1:
            case 2:
            case 3:
            case 4:
                $retType = "load";
                break;
            case 5:
            case 6:
            case 7:
                $retType = "truck";
                break;
            default:
                $retType = "element";                
        }

        echo json_encode(array("error" => 0, "retType" => $retType));
    }

    public function distancebtw($from , $to)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', 'https://maps.googleapis.com/maps/api/distancematrix/json?departure_time=now&destinations='.$from.'&origins='.$to.'&key=AIzaSyCsEtLFAR_7CkTeUiXCYUK-lgz44Ix2Xjs', [
                'form_params' => []
            ]);

            $results= json_decode($res->getBody(), true);

            return $results ;
        } catch (\Throwable $th) {
            return null;
        }        
    }
    
    public function matching()
    {
        return Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0];
    }

    /**
    * Delete the route from the database.
    *
    * @param  Request $request
    * @return \Illuminate\Http\Response
    */
    public function destroyer(Request $request) 
    {
        $ret = Trajet::destroy($request->id);        
        if($ret) {
            return json_encode(array(
                "statusCode" => 200
            ));
        } else {
            return json_encode(array(
                "statusCode" => 400                
            ));
        }        
    }
    
    /**
    * Generate all the routes as text for the user.
    *
    * @return JSON Object
    */
    public function getRouteList() {
        
        $typeManager = getManagerType();        
        
        if($typeManager === "LM") {
            $data = Trajet::whereIn("zone_id", [1, 2, 3, 4])->get();
        } else if($typeManager === "TM") {
            $data = Trajet::whereIn("zone_id", [5, 6, 7])->get();
        } else if($typeManager === "Admin") {
            $data = Trajet::all();
        }

        if ($data->isEmpty()) { 
            echo json_encode("There are currently no loads or trucks available.");  
        }

        $retArr = array();        
        foreach($data as $route) {            
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
            $displayText = $route->from_others . " -> " . $route->to_others;

            // Insert in an array the country code and the display text used for the email
            array_push($retArr, ["countryCode" => $countryDeparture, "label" => $displayText]);
        }
        
        // Ordering our return array in a ascendant way
        array_multisort($retArr, SORT_ASC);        
        // dd($retArr);

        $finalStr = "";
        $tmp = "";
        foreach($retArr as $elem) {
          if($tmp != $elem['countryCode']) {
            $tmp = $elem['countryCode'];
            $finalStr .= PHP_EOL.PHP_EOL.$tmp.PHP_EOL.PHP_EOL;
          }
          $finalStr .= $elem['label'].PHP_EOL.PHP_EOL;
        }

        echo json_encode($finalStr);      
  }

}
