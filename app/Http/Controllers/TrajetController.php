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

class TrajetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type_manager = Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["type"];
        $zones = Zone::get(['zones.zone_name']);
        if( !empty( $request->except('_token') ) ){
            //code...
        
            $managerid = $request->get('managerid');
            if ($managerid == 'test'){
                $type_search = "all";
                $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
                        ->orderBy('trajets.date_depart', 'DESC')
                        ->get(['trajets.id','trajets.date_depart','zones.zone_name','trajets.from_others','trajets.to_others','trajets.key','trajets.distance','trajets.duration','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars','trajets.manager_id']);
            }else if ($managerid != 'test'){
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
        return  view('manager.trajets.index', compact('zones') , compact('data') )->with('type_manager',$type_manager)->with('type_search',$type_search)->with('test',self::test());

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $zones = Zone::get();
        $countries  = Country::orderBy('fullname')->get();
        return view('manager.trajets.create')
        ->with(compact('zones'))
        ->with(compact('request'))
        ->with(compact('countries'));
    }

    public function searchcity(Request $request)
    {

        if($request->ajax()) {
          
            $data =City::join('countries', 'countries.id', '=', 'cities.coutry_id')
                        ->where('coutry_id', '=',$request->country_id)
                        ->orderBy('city_name')
                        ->get(['countries.country_code','cities.city_name']);

                        
            return $data;
           
            
        }
    }
    public function test()
    {
        $type_manager = Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["type"];
        $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
                    ->orderBy('trajets.date_depart', 'DESC')
                    ->get(['trajets.id','trajets.zone_id','trajets.date_depart','zones.zone_name','trajets.from_others','trajets.to_others','trajets.key','trajets.distance','trajets.duration','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars','trajets.manager_id']);
        
        $text = "";
        $countries = ["CH", "ES", "FR", "UK", "PT","CY","AT", "BE", "DE", "DK", "FI", "IS", "IT", "LU", "NL", "NO", "SE","BG", "CZ", "EE", "HR", "HU", "LT", "LV", "PL", "RO", "RS", "SI", "SK"];
        foreach ($data as $key) {
            if($type_manager=="LM"){
                if( in_array($key->zone_id, array(1,2,3,4))){
                    $from_others = $key->from_others;
                    $to_others = $key->to_others;
                    $conc = $from_others. " - " .$to_others ."\n" ;
                    $text = $text  . $conc; 
                }
            }
            if($type_manager=="TM"){
                if( in_array($key->zone_id, array(5,6,7))){
                    $from_others = $key->from_others;
                    $to_others = $key->to_others;
                    $conc = $from_others. " - " .$to_others ."\n" ;
                    $text = $text  . $conc; 
                }
            }

        }
        $text2 = "";
        foreach ($countries as $code) {
            $country_code = "";
            $ch_cd = "";
            $loads = array();
            foreach ($data as $key) {
                if($type_manager=="LM"){
                    if( in_array($key->zone_id, array(1,2,3,4))){
                        $country = $key->from_others;
                        $country = explode('(', explode(')', $country)[0])[1];
                        if($code == $country ){
                            if ($country_code == ""){
                                $ch_cd = "1";
                                $country_code = $country;
                                $text2 = $text2 . "\n". $country_code. "\n";
                                $textload = "";
                                if ($key->vans != 0){
                                    $textload = $textload . $key->vans . " Vans ";
                                }
                                if (strval($key->full_load) == "1"){
                                    $textload = $textload . "FL ";
                                }
                                if ($key->used_cars == 1){
                                    $textload = $textload . ": UC ";
                                }
                                
                                $text2 = $text2 . $key->from_others . " - " .$key->to_others. " | " .$textload. "\n" ;
                            }else{
                                $textload = "";
                                if ($key->vans != 0){
                                    $textload = $textload . $key->vans. " Vans ";;
                                }
                                if (strval($key->full_load) == "1"){
                                    $textload = $textload . "FL";
                                }
                                if ($key->used_cars == 1){
                                    $textload = $textload . ":UC";
                                }
                                $text2 = $text2 . $key->from_others . " - " .$key->to_others. " | " .$textload. "\n" ;
                                $textload = "";
                            }
                            
                        }
                        if($ch_cd != "1" ){
                            
                        }
                    }
                }
                if($type_manager=="TM"){
                    if( in_array($key->zone_id, array(5,6,7))){
                        $country = $key->from_others;
                        $country = explode('(', explode(')', $country)[0])[1];
                        if($code == $country ){
                            if ($country_code == ""){
                                $ch_cd = "1";
                                $country_code = $country;
                                $text2 = $text2 . "\n". $country_code. "\n";
                                $textload = "";
                                if ($key->vans != 0){
                                    $textload = $textload . $key->vans . " Vans ";
                                }
                                if (strval($key->full_load) == "1"){
                                    $textload = $textload . "FL ";
                                }
                                if ($key->used_cars == 1){
                                    $textload = $textload . ": UC ";
                                }
                                
                                $text2 = $text2 . $key->from_others . " - " .$key->to_others. " | " .$textload. "\n" ;
                            }else{
                                $textload = "";
                                if ($key->vans != 0){
                                    $textload = $textload . $key->vans. " Vans ";;
                                }
                                if (strval($key->full_load) == "1"){
                                    $textload = $textload . "FL";
                                }
                                if ($key->used_cars == 1){
                                    $textload = $textload . ":UC";
                                }
                                $text2 = $text2 . $key->from_others . " - " .$key->to_others. " | " .$textload. "\n" ;
                                $textload = "";
                            }
                            
                        }
                        if($ch_cd != "1" ){
                            
                        }
                    }
                }
                

            }
            
        }
        
        return $text2;
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date_depart=$request->get('date_depart');
        $zone_select=$request->get('zone_select');
        $key=$request->key_radios;
        if ($key == 'key'){
            $key = 1;
        }else{
            $key = 0;
        }
        
        $stars=$request->get('stars_select');
        $from_others=$request->get('from_cities');
        $to_others=$request->get('to_cities');
        $comment=$request->get('comment_trajet');

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
        $used_cars = $request->get('usedcars');
        if ($used_cars == "checked"){
            $used_cars = 1;
        }else{
            $used_cars = 0;
        }
        if ($zone_select == 1 || $zone_select == 2 || $zone_select == 3){
            $vans = $request->get('btnradio');
            $full_load = 1;
            
            
        }
        if ($zone_select == 4){
            $vans = $request->get('btnradio');
            $full_load = 0;
        }
        if ($zone_select == 5){
            $vans = 11;
            $full_load = 1;
        }
        if ($zone_select == 6){
            $vans = $request->get('btnradio');
            $full_load = 0;
        }
        if ($zone_select == 7){
            $vans = $request->get('btnradio');
            $full_load = 1;
        }

        

        $insertData=Trajet::insert(
            [
                'date_depart' => $date_depart,
                'zone_id' => $zone_select,
                'distance' => $distance,
                'duration' => $duration,
                'manager_id' => Manager::with('user')->where("user_id","=",Auth::user()->id)->get("id")[0]["id"],
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
        return redirect()->route('manager.trajets.index')
                        ->with('success','Trajet created successfully.');
        
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Manager\Trajet  $trajet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trajet $trajet)
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
}
