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
                                ->orderBy('trajets.date_depart', 'DESC')
                                ->get(['trajets.id','trajets.date_depart','zones.zone_name','trajets.from_others','trajets.to_others','trajets.key','trajets.distance','trajets.duration','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars','trajets.manager_id']);
            } else if($managerid != 'test') {
                $type_search = "bymanager";
                $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
                                ->where('trajets.manager_id','=',$managerid)
                                ->orderBy('trajets.date_depart', 'DESC')
                                ->get(['trajets.id','trajets.date_depart','zones.zone_name','trajets.from_others','trajets.to_others','trajets.key','trajets.distance','trajets.duration','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars','trajets.manager_id']);
            }
        } else {
            //throw $th;
            $type_search = "all";
            $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
                            ->orderBy('trajets.date_depart', 'DESC')
                            ->get(['trajets.id','trajets.date_depart','zones.zone_name','trajets.from_others','trajets.to_others','trajets.key','trajets.distance','trajets.duration','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars','trajets.manager_id']);        
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
        // Var attribution for validation and insert
        $date_depart = $request->date_depart;
        $zone_select = $request->zone_select;        
        $key = ($request->key_radios === "key") ? 1 : 0;        
        $stars = $request->stars_select;
        $from_others = $request->from_cities;
        $to_others = $request->to_cities;
        
        $results =  self::distancebtw(str_replace('+', '|', $from_others),str_replace('+', '|', $to_others));
        try {
            $distance = $results["rows"][0]["elements"][0]["distance"]["value"];
            $duration = $results["rows"][0]["elements"][0]["duration"]["value"];
        } catch (\Throwable $th) {
            $distance = "0";
            $duration = "0";
        }
        
        $vans = 0;
        $full_load = 0;
        
        switch($zone_select) {
          case 1:
          case 2:
          case 3:
            $vans = $request->get('btnradio');
            $full_load = 1;
          case 4:
            $vans = $request->get('btnradio');
            $full_load = 0;
          case 5:
            $vans = 11;
            $full_load = 1;
          case 6:
            $vans = $request->get('btnradio');
            $full_load = 0;
          case 7:
            $vans = $request->get('btnradio');
            $full_load = 1;
          default:
            break;
        }

        $used_cars = ($request->get('usedcars') === "checked") ? 1 : 0;
        $comment = $request->comment_trajet;

        // Validation
        if(
          $date_depart === null || $date_depart < date('Y-m-d') || 
          $zone_select === null || ($zone_select < 1 || $zone_select > 7) ||
          $key != 1 && $key != 2 ||
          $stars === null || ($stars != 1 && $stars != 2 && $stars != 3) ||
          $from_others === null || strlen($from_others) > 191 ||
          $to_others === null || strlen($to_others) > 191 ||
          $vans < 1 || $vans > 11 || $vans === "on"
        ) {
          return redirect()->route('manager.trajets.index')->with('validationError','There has been an error during the creation, please retry.');
        }
        
        // Insert
        $insertData = Trajet::insert(
            [
                'date_depart' => $date_depart,
                'zone_id' => $zone_select,
                'distance' => $distance,
                'duration' => $duration,
                'manager_id' => getManagerId(),
                'key' => $key,
                'stars' => $stars,
                'from_others' => $from_others ,
                'to_others' => $to_others ,
                'comment' => $comment ,
                'vans' => $vans,
                'full_load' => $full_load,
                'used_cars' => $used_cars
            ]
        );
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

    public function duplicate(Request $request){
        $old_trajet_id = $request->trajet_id;
        $data = Trajet::where('id','=',$old_trajet_id)->get();
        
        $new_date = Carbon::createFromFormat('Y-m-d', $data[0]->date_depart);
        $daysToAdd = 1;
        $new_date = $new_date->addDays($daysToAdd)->format('Y-m-d');

        $newTask = $data[0]->replicate();
        $newTask->date_depart = $new_date; // the new project_id
        $newTask->save();

        return $new_date;
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
    public function matching(){
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
