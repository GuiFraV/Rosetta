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
    public function index()
    {
        try {
            $search = $request->get('searchbar');
            $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
                    
                    ->where('trajets.date_depart','like','%'.$search.'%')
                    
                    ->get(['trajets.id','trajets.date_depart','zones.zone_name','trajets.from_others','trajets.to_others','trajets.key','trajets.distance','trajets.duration','trajets.stars','trajets.comment']);

        } catch (\Throwable $th) {
            $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
            ->join('managers','managers.id','=','trajets.manager_id')
            ->orderBy('trajets.date_depart', 'DESC')
            ->get(['trajets.id','trajets.date_depart','zones.zone_name','trajets.from_others','trajets.to_others','trajets.key','trajets.distance','trajets.duration','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars','managers.type']);        
        }
        $type_manager = Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["type"];
        $zones = Zone::get(['zones.zone_name']);
        
        

        return view('manager.trajets.index', compact('zones') , compact('data') )->with('type_manager',$type_manager);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $zones = Zone::get();
        $cities = City::orderBy('city_name')->get();
        $countries  = Country::orderBy('country_name')->get();
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
        return view('test');
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
            // if ($request->used_cars)){
            //     $used_cars = 1;
            // }else{
            //     $used_cars = 0;
            // }
            
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
