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
        $countries  = Country::where("isActive", 1)->orderBy('fullname')->get();
        $zones = Zone::get(['zones.id', 'zones.zone_name']);

        $results = Trajet::query();
        $results->join('zones', '.zone_id', '=', 'trajets.zone_id');
        $results->where('trajets.visible', ">=", '0');
        
        $srcCount = 0;

        if ($request->srcDepartureCity != null) {
            $results = $results->where('from_others', 'like', '%' . $request->srcDepartureCity . '%');
            $srcCount += 2;
        } else if($request->srcDepartureCountry != null) {
            $results = $results->where('from_others', 'like', '%(' . $request->srcDepartureCountry . ')%');
            $srcCount++;
        }
        
        if ($request->srcArrivalCity != null) {
            $results = $results->where('to_others', 'like', '%' . $request->srcArrivalCity . '%');
            $srcCount += 2;
        } else if($request->srcArrivalCountry != null) {
            $results = $results->where('to_others', 'like', '%(' . $request->srcArrivalCountry . ')%');
            $srcCount++;
        }
        
        if ($request->srcManager != null) {
            $results = $results->where('trajets.manager_id', '=', $request->srcManager);
            $srcCount++;
        }
        
        if ($request->srcZone != null) {
            $results = $results->where('trajets.zone_id', '=', $request->srcZone);
            $srcCount++;
        }

        if ($request->srcDate != null) {
            $results = $results->where('trajets.date_depart', '=', $request->srcDate);
            $srcCount++;
        }
        
        $results->orderBy('trajets.id', 'DESC');
        $data = $results->get(['trajets.id','trajets.date_depart','zones.zone_name', 'trajets.manager_id', 'trajets.from_others','trajets.to_others','trajets.distance','trajets.duration','trajets.key','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars','trajets.urgent', 'trajets.visible', 'trajets.created_at', 'trajets.matched_to']);
        
        return view('manager.trajets.index', compact('zones') , compact('data'))->with('type_manager', $type_manager)->with('countries', $countries)->with('srcCount', $srcCount); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $countries  = Country::where("isActive", 1)->orderBy('fullname')->get();

        // Motivation quotes
        $type = (getManagerType() === "LM") ? "truck" : "load" ;
        
        $monday = "Give me some " . $type . "s!";
        $tuesday = "Do you have a " . $type . " for me?";
        $wednesday = "We need more " . $type . "s!";
        $thursday = "More " . $type . " guys!";
        $friday = "Be sure you don't forget a " . $type . "!";
        $arrayWeek = array($monday, $tuesday, $wednesday, $thursday, $friday);
        $selected = date('N', strtotime('Today'));
        
        // This week Route statistic
        $arrSelectZone = ($type === "truck") ? [5, 6, 7] : [1, 2, 3, 4];
        $lastMonday = date('Y-m-d H:i:s',strtotime('Monday this week'));
        $routesThisWeek = Trajet::where('created_at', '>=', $lastMonday)->whereIn("zone_id", $arrSelectZone)->get();
        $res = count($routesThisWeek);
        
        $arrayHeadsOrTails = array();        
        $statistic = ($res <= 1) ? "There has been ".$res." " . $type . " since monday!" : "There has been ".$res." " . $type . "s since monday!";
        array_push($arrayHeadsOrTails, $statistic);
        if ($selected >= 1 && $selected <= 5) {
            array_push($arrayHeadsOrTails, $arrayWeek[$selected-1]);
        }
        $quote = $arrayHeadsOrTails[array_rand($arrayHeadsOrTails, 1)];

        return view('manager.trajets.create')->with('quote', $quote)                                            
                                            ->with(compact('request'))
                                            ->with(compact('countries'));
    }

    public function searchcity(Request $request) {
        if($request->ajax()) {          
            $data = City::join('countries', 'countries.id', '=', 'cities.coutry_id')
                        ->where('coutry_id', '=', $request->country_id)
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
        $from_others = $request->from_cities;
        $to_others = $request->to_cities;
        
        $termFrom = str_replace('+', '|', $from_others);
        $termTo = str_replace('+', '|', $to_others);
                
        $unified = $termFrom . " | " . $termTo;	
        $unified = str_replace('+', '|', $unified);
        
        $distance = 0;
        $duration = 0;
        
        $arrExp = explode(" | ", $unified);
        
        for($i=1; $i<count($arrExp); $i++) {
            $results = self::distancebtw($arrExp[$i-1], $arrExp[$i]);            
            try {
                $distance += $results["rows"][0]["elements"][0]["distance"]["value"];
                $duration += $results["rows"][0]["elements"][0]["duration"]["value"];
            } catch (\Throwable $th) {
                continue;
            }
        }

        $arrExp = explode(" | ", $termFrom);
        $from_coordinates = "";
        $multipleIteration = 0;
        foreach($arrExp as $curr) {                    
        	$from_coordinates .= ($multipleIteration === 0) ? self::getCoordinates($curr) : ";".self::getCoordinates($curr);
          $multipleIteration++;
        }
        
        $arrExp = explode(" | ", $termTo);
        $to_coordinates = "";
        $multipleIteration = 0;
        foreach($arrExp as $curr) {                    
        	$to_coordinates .= ($multipleIteration === 0) ? self::getCoordinates($curr) : ";".self::getCoordinates($curr);
          $multipleIteration++;
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
        $urgent = ($request->has('urgentRoute')) ? 1 : 0;        

        $tmpType = getManagerType();        
        $zone_id = 0;
        $arrZoneOne = array("EE", "LV", "LT", "CZ", "SK", "PL", "HU", "BG", "HR", "GR", "MT", "RO", "SI");
        $arrZoneTwo = array("DE", "AT", "IT", "BE", "NL", "LU", "DK", "SE", "NO", "FI");
        $arrZoneThree = array("FR", "ES", "PT", "GB", "CH", "IE");
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

        // Validation        
        if($date_depart === null || $date_depart < date('Y-m-d', strtotime("yesterday"))) {
            return redirect()->route('manager.trajets.index')->with('validationError', 'Form error! Please check the departure date.');
        } elseif($zone_id === 0 || $zone_id === null || ($zone_id < 1 || $zone_id > 7)) {
            return redirect()->route('manager.trajets.index')->with('validationError', 'There has been an error during the creation, please retry.');
        } elseif($key != 0 && $key != 1) {
            return redirect()->route('manager.trajets.index')->with('validationError', 'Form error! Please select a key.');
        } elseif($stars === null || ($stars != 1 && $stars != 2 && $stars != 3)) {
            return redirect()->route('manager.trajets.index')->with('validationError', 'Form error! Please select a number of stars.');
        } elseif($from_others === null || strlen($from_others) > 191) {
            return redirect()->route('manager.trajets.index')->with('validationError', 'Form error! The loading city is missing or incorrect.');
        } elseif($to_others === null || strlen($to_others) > 191) {
            return redirect()->route('manager.trajets.index')->with('validationError', 'Form error! The unloading city is missing or incorrect.');
        } elseif($vehicles < 0 || $vehicles > 11) {
            return redirect()->route('manager.trajets.index')->with('validationError', 'Form error! The number of vehicles is incorrect.');
        }
        
        // dd($request);

        // Insertion
        $insertData = Trajet::insert([
            'date_depart' => $date_depart,
            'zone_id' => $zone_id,
            'manager_id' => getManagerId(),
            'from_others' => $from_others,
            'to_others' => $to_others,
            'from_coordinates' => $from_coordinates,
            'to_coordinates' => $to_coordinates,
            'distance' => $distance,
            'duration' => $duration,                
            'key' => $key,
            'stars' => $stars,                
            'comment' => $comment ,
            'vans' => $vehicles,
            'full_load' => $full_load,
            'used_cars' => $used_cars,
            'urgent' => $urgent
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {        
        try {
            $trajet = Trajet::findOrFail($request->id);
            // Not returned values
            // id, zone_id, manager_id, from_coordinates, to_coordinates, distance, duration, visible, matched_to
            $intergate_truck = ($trajet->zone_id === 7) ? 1 : 0;
            return json_encode(
              array(
                  "error" => 0,
                  "trajet" => array (
                      "date_depart" => $trajet->date_depart,
                      "from_others" => $trajet->from_others,
                      "to_others" => $trajet->to_others,
                      "key" => $trajet->key,
                      "stars" => $trajet->stars,
                      "comment" => $trajet->comment,
                      "vans" => $trajet->vans,
                      "full_load" => $trajet->full_load,
                      "used_cars" => $trajet->used_cars,
                      "urgent" => $trajet->urgent,
                      "intergate_truck" => $intergate_truck
                  ) 
              )
          );
        } catch(ModelNotFoundException $e) {
            return json_encode(
                array(
                    "error" => 1,
                    "message" => "This functionnality is unavailable. Please reload the page and retry."
                )
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request     
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            // Var attribution for validation  
            $trajet = Trajet::findOrFail($request->id);
            
            $date_depart = $request->date_depart;
            $from_others = $request->from_cities;
            $to_others = $request->to_cities;

            $termFrom = str_replace('+', '|', $from_others);
            $termTo = str_replace('+', '|', $to_others);
            $results = self::distancebtw($termFrom, $termTo);
            try {
                $distance = $results["rows"][0]["elements"][0]["distance"]["value"];
                $duration = $results["rows"][0]["elements"][0]["duration"]["value"];
            } catch (\Throwable $th) {
                $distance = "0";
                $duration = "0";
            }
          
            $arrExp = explode(" | ", $termFrom);
            $from_coordinates = "";
            $multipleIteration = 0;
            foreach($arrExp as $curr) {                    
            	  $from_coordinates .= ($multipleIteration === 0) ? self::getCoordinates($curr) : ";".self::getCoordinates($curr);
                $multipleIteration++;
            }
            $arrExp = explode(" | ", $termTo);
            $to_coordinates = "";
            $multipleIteration = 0;
            foreach($arrExp as $curr) {                    
            	  $to_coordinates .= ($multipleIteration === 0) ? self::getCoordinates($curr) : ";".self::getCoordinates($curr);
                $multipleIteration++;
            }
            
            $key = ($request->key_radios === "key") ? 1 : 0;        
            $stars = $request->stars;
            $comment = $request->comment_trajet;

            $vehicles = $request->vanNumber;
            $full_load = 0;

            if($vehicles === "on") {
              $vehicles = 0;
              $full_load = 1;
            }

            $used_cars = $request->used_cars;
            $urgent = $request->urgent;

            $tmpType = getManagerType();        
            $zone_id = 0;
            $arrZoneOne = array("EE", "LV", "LT", "CZ", "SK", "PL");
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
                if($request->intergateTruck) {
                    $zone_id = 7;
                } elseif($full_load) {
                    $zone_id = 5;
                } else {
                    $zone_id = 6;
                }
            }        

            // Validation        
            if($date_depart === null || $date_depart < date('Y-m-d', strtotime("yesterday"))) {
                echo json_encode(array("error" => 0, "message" => "Form error! Please check the departure date."));                
            } elseif($zone_id === 0 || $zone_id === null || ($zone_id < 1 || $zone_id > 7)) {                
                echo json_encode(array("error" => 0, "message" => "There has been an error during the update, please retry."));
            } elseif($key != 0 && $key != 1) {
                echo json_encode(array("error" => 0, "message" => "Form error! Please select a key."));                
            } elseif($stars === null || ($stars != 1 && $stars != 2 && $stars != 3)) {
                echo json_encode(array("error" => 0, "message" => "Form error! Please select a number of stars."));                
            } elseif($from_others === null || strlen($from_others) > 191) {
                echo json_encode(array("error" => 0, "message" => "Form error! The loading city is missing or incorrect."));                
            } elseif($to_others === null || strlen($to_others) > 191) {
                echo json_encode(array("error" => 0, "message" => "Form error! The unloading city is missing or incorrect."));                
            } elseif($vehicles < 0 || $vehicles > 11) {
                echo json_encode(array("error" => 0, "message" => "Form error! The number of vehicles is incorrect."));                
            }

            // Update
            $trajet->date_depart = $date_depart;
            $trajet->zone_id = $zone_id;            
            $trajet->from_others = $from_others;
            $trajet->to_others = $to_others;
            $trajet->from_coordinates = $from_coordinates;
            $trajet->to_coordinates = $to_coordinates;
            $trajet->distance = $distance;
            $trajet->duration = $duration;                
            $trajet->key = $key;
            $trajet->stars = $stars;            
            $trajet->comment = $comment;
            $trajet->vans = $vehicles;
            $trajet->full_load = $full_load;
            $trajet->used_cars = $used_cars;
            $trajet->urgent = $urgent;
            $updateStatus = $trajet->save();
            
            if($updateStatus) {

                $retHTML = ($trajet->urgent) ? '<tr id="ln'.$trajet->id.'" class="text-danger"' : '<tr id="ln'.$trajet->id.'" ';
                if(isset($trajet->matched_to))
                  $retHTML .= 'class="text-decoration-line-through"';
                $retHTML .= '>';
                $retHTML .= '<td><a href="#" from_l="'.$trajet->from_others.'" to_l="'.$trajet->to_others.'" typebtn="openmapps"><span style="color: Dodgerblue;" title="Open Maps" class="fa fa-map-marked-alt" ></span></a></td>';
                $retHTML .= '<td style="font-size: 75%">'.getManagerName($trajet->manager_id, '').'</td>';              
                $retHTML .= '<td style="font-size: 75%">'.date('d-m-Y', strtotime($trajet->date_depart)).'</td>';
                $retHTML .= '<td style="font-size: 75%">'.$trajet->from_others.'</td>';
                $retHTML .= '<td style="font-size: 75%">'.$trajet->to_others.'</td>';
                if($trajet->distance === 0)
                    $retHTML .= '<td style="font-size: 75%">NaN</td>';
                else
                    $retHTML .= '<td style="font-size: 75%">'.(int)(($trajet->distance)/1000).' Km</td>';
                
                $retHTML .= '<td style="font-size: 75%">';
                if($trajet->vans != 0)
                    $retHTML .= $trajet->vans.'<span class="fa fa-car" style="align-self: center"></span>';
                if(strval($trajet->full_load) == "1")
                    $retHTML .= 'FL';
                if($trajet->used_cars == 1)
                    $retHTML .= ':UC';
                $retHTML .= '</td>';
                $retHTML .= ($trajet->key == 1) ? '<td><span class="fa fa-key" style="align-self: center"></span></td>' : '<td style="font-size: 75%"></td>';
                $retHTML .= '<td>';
                if ($trajet->stars == 1)
                    $retHTML .= '<span class="far fa-star" style="align-self: center" title="*"></span>';
                elseif ($trajet->stars == 2)
                    $retHTML .= '<span class="fas fa-star-half-alt" style="align-self: center" title="**"></span>';               
                elseif ($trajet->stars == 3)
                    $retHTML .= '<span class="fas fa-star" style="align-self: center" title="***"></span>';
                $retHTML .= '</td>';
                $retHTML .= '<td style="font-size: 75%">'.date('H:i', strtotime($trajet->created_at)).'</td>';
                if (isset($trajet->comment))
                    $retHTML .= '<td><a role="button" class="bi bi-chat-square-text text-warning" style="font-size: 1.4rem;" id="buttonComment" onclick="" data-bs-toggle="tooltip" title="" data-bs-original-title="'. $trajet->comment .'"></a></td>';
                else 
                    $retHTML .= '<td></td>';              
                if (!isset($trajet->matched_to) && $trajet->manager_id === getManagerId())
                    $retHTML .= '<td><a role="button" class="bi bi-arrows-collapse text-success" style="font-size: 1.4rem;" title="Match" onclick="openMatchModal('.$trajet->id.'); $(\'#idInitialElementMatch\').val('. $trajet->id .'); $(\'#maxKilometersMatch\').val(150); $(\'#actualRangeVal\').html(\'150Km\');"></a></td>';
                else
                    $retHTML .= '<td></td>';              
                if ($trajet->visible === 0 && $trajet->manager_id === getManagerId())                    
                    $retHTML .= '<td><a role="button" class="bi bi-node-plus text-primary" style="font-size: 1.4rem;" id="buttonDuplicate" title="Duplicate" onclick="duplicate(this, '.$trajet->id.')"></a></td>';
                elseif ($trajet->visible > 0 && $trajet->manager_id === getManagerId())
                    $retHTML .= '<td><a role="button" class="bi bi-node-minus text-danger" style="font-size: 1.4rem;" id="buttonUnduplicate" title="Cancel Duplication" onclick="unduplicate(this, '.$trajet->id.')"></a></td>';
                if ($trajet->manager_id === getManagerId())
                    $retHTML .= '<td><a role="button" class="bi bi-pencil text-warning" style="font-size: 1.4rem;" title="Update" onclick="openModalEdit('.$trajet->id.'); $(\'#updatedId\').val('.$trajet->id.');"></a></td>';
                else
                    $retHTML .= '<td></td>';
              
                if ($trajet->manager_id === getManagerId())
                    $retHTML .= '<td><a role="button" class="bi bi-trash text-danger" style="font-size: 1.4rem;" title="Delete" onclick="$(\'#destroyModal\').modal(\'show\'); $(\'#destroyedId\').val('.$trajet->id.');"></a></td>';
                else
                    $retHTML .= '<td></td>';
                $retHTML .= '</tr>';
                
                return json_encode(array(
                    'error' => 0,
                    'message' => 'The route has been updated successfully.',
                    'id' => $trajet->id,
                    'zone' => $trajet->zone_id,
                    'retHTML' => $retHTML
                ));
            } 
            // Else, no or more Routes than expected have or not been updated.
        } catch(ModelNotFoundException $e) {
            return json_encode(
                array(
                    "error" => 1,
                    "message" => "This functionnality is unavailable. Please reload the page and retry."
                )
            );
        }
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
            $results = json_decode($res->getBody(), true);
            return $results ;
        } catch (\Throwable $th) {
            return null;
        }        
    }
    
    public static function getCoordinates($city)
    {
        try {
            $client = new Client();
            $res = $client->request('GET', 'https://maps.googleapis.com/maps/api/geocode/json?address='.$city.'&key=AIzaSyCsEtLFAR_7CkTeUiXCYUK-lgz44Ix2Xjs', [
                'form_params' => []
            ]);
            $results = json_decode($res->getBody(), true);
            if(!empty($results)) {
                $resConcat = $results["results"][0]["geometry"]["location"]["lat"];
                $resConcat .= ",".$results["results"][0]["geometry"]["location"]["lng"];
                return $resConcat ;
            } else {
                return null;
            }            
        } catch (\Throwable $th) {
            return null;
        }
    }
    
    /**
    * Get the list of matching trucks / loads for the matching modal
    *
    * @param  Request $request
    * @return \Illuminate\Http\Response
    **/
    public function getMatchingList(Request $request)
    {
        $distanceParameter = 150;        
        $route = Trajet::findOrFail($request->id);        

        $retType = "";
        switch ($route->zone_id) {
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

        $dispNumberVehicles = ($route->full_load === 1) ? "FL" : $route->vans . "C";
        $dispUsedCars = ($route->used_cars === 1) ? "UC" : "";        
        $initialMatch = "<button type='button' value='".$request->id."' class='list-group-item list-group-item-action disabled text-light' style='background-color: #0275d8; border-color: #0275d8;'>".$route->from_others . " -> " . $route->to_others . " - " . round($route->distance/1000, 1) ."Km | ". $dispNumberVehicles . " " . $dispUsedCars."</button>";

        if ($retType === "load") {
            $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
            ->where('trajets.visible', ">=", '0')
            ->whereIn("trajets.zone_id", [5, 6, 7])            
            ->orderBy('trajets.id', 'DESC')
            ->get(['trajets.id','trajets.date_depart','zones.zone_name', 'trajets.manager_id', 'trajets.from_others','trajets.to_others', 'trajets.from_coordinates', 'trajets.to_coordinates','trajets.distance','trajets.duration','trajets.key','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars', 'trajets.visible', 'trajets.created_at']);
        } elseif ($retType === "truck") {
            $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
            ->where('trajets.visible', ">=", '0')
            ->whereIn("trajets.zone_id", [1, 2, 3, 4])            
            ->orderBy('trajets.id', 'DESC')
            ->get(['trajets.id','trajets.date_depart','zones.zone_name', 'trajets.manager_id', 'trajets.from_others','trajets.to_others', 'trajets.from_coordinates', 'trajets.to_coordinates','trajets.distance','trajets.duration','trajets.key','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars', 'trajets.visible', 'trajets.created_at']);
        }

        // Explodes the coordinates of the initial loading place into an array
        $arrFromCoord = explode(';', $route->from_coordinates);
        foreach ($arrFromCoord as $key=>$value) {
            $arrFromCoord[$key] = explode(',', $value);
        }

        if (empty($arrFromCoord[0][0]) || empty($arrFromCoord[0][1])) {
            return 1;
        } 
            
        $initialLat = $arrFromCoord[0][0];
        $initialLong = $arrFromCoord[0][1];
                
        $arrayRes = array();
        foreach($data as $trajet) {  
            
            // Explodes the coordinates in the DB into an array
            $arrFromCoord = explode(';', $trajet->from_coordinates);
            foreach ($arrFromCoord as $key=>$value) {
                $arrFromCoord[$key] = explode(',', $value);
            }
          
            if (empty($arrFromCoord[0][0]) || empty($arrFromCoord[0][1])) {
                continue;
            } 
            
            $tmpLat = $arrFromCoord[0][0];
            $tmpLong = $arrFromCoord[0][1];
                      
            $approxDistance = vincentyGreatCircleDistance($initialLat, $initialLong, $tmpLat, $tmpLong);
            
            if($approxDistance/1000 < $distanceParameter) {
                $tmpNumberVehicles = ($trajet->full_load === 1) ? "FL" : $trajet->vans . "C";
                $tmpUsedCars = ($trajet->used_cars === 1) ? "UC" : "";
                $tmpMatch = getManagerName($trajet->manager_id, "") . " (".date('H:i', strtotime($trajet->created_at)).")" . " | " . $trajet->from_others . " -> " . $trajet->to_others . " - " . round($trajet->distance/1000, 1) ."Km | ". $tmpNumberVehicles . " " . $tmpUsedCars . " | " . round($approxDistance/1000, 2) . "Km";
                array_push($arrayRes, ["id" => $trajet->id, "label" => $tmpMatch]);
            }                                    
        }
        
        if(empty($arrayRes))
            return json_encode(array("error" => 1, "message" => "There are currently no matches for this " . $retType . "!"));  
      
        $lsMatches = "";
        foreach($arrayRes as $match) {
            $lsMatches .= "<button type='button' value='".$match['id']."' class='list-group-item list-group-item-action'>".$match['label']."</button>";
        }

        return json_encode(array(
          "error" => 0,
          "type" => $retType,
          "initialMatch" => $initialMatch,
          "lsMatches" => $lsMatches          
        ));
    }
    
    /**
    * Get the list of matching trucks / loads for the matching modal
    *
    * @param  Request $request
    * @return \Illuminate\Http\Response
    **/
    public function refreshMatchingList(Request $request)
    {        
        $distanceParameter = $request->kmParam;        
        $route = Trajet::findOrFail($request->id);

        $retType = "";
        switch ($route->zone_id) {
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

        if ($retType === "load") {
            $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
            ->where('trajets.visible', ">=", '0')
            ->whereIn("trajets.zone_id", [5, 6, 7])
            ->orderBy('trajets.id', 'DESC')
            ->get(['trajets.id','trajets.date_depart','zones.zone_name', 'trajets.manager_id', 'trajets.from_others','trajets.to_others', 'trajets.from_coordinates', 'trajets.to_coordinates','trajets.distance','trajets.duration','trajets.key','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars', 'trajets.visible', 'trajets.created_at']);
        } elseif ($retType === "truck") {
            $data = Trajet::join('zones', 'zones.id', '=', 'trajets.zone_id')
            ->where('trajets.visible', ">=", '0')
            ->whereIn("trajets.zone_id", [1, 2, 3, 4])
            ->orderBy('trajets.id', 'DESC')
            ->get(['trajets.id','trajets.date_depart','zones.zone_name', 'trajets.manager_id', 'trajets.from_others','trajets.to_others', 'trajets.from_coordinates', 'trajets.to_coordinates','trajets.distance','trajets.duration','trajets.key','trajets.stars','trajets.comment','trajets.vans','trajets.full_load','trajets.used_cars', 'trajets.visible', 'trajets.created_at']);
        }

        // Explodes the coordinates of the initial loading place into an array
        $arrFromCoord = explode(';', $route->from_coordinates);
        foreach ($arrFromCoord as $key=>$value) {
            $arrFromCoord[$key] = explode(',', $value);
        }

        if (empty($arrFromCoord[0][0]) || empty($arrFromCoord[0][1])) {
            return 1;
        } 
            
        $initialLat = $arrFromCoord[0][0];
        $initialLong = $arrFromCoord[0][1];
        
        $arrayRes = array();
        foreach($data as $trajet) {  
            
            // Explodes the coordinates in the DB into an array
            $arrFromCoord = explode(';', $trajet->from_coordinates);
            foreach ($arrFromCoord as $key=>$value) {
                $arrFromCoord[$key] = explode(',', $value);
            }
          
            if (empty($arrFromCoord[0][0]) || empty($arrFromCoord[0][1])) {
                continue;
            } 
            
            $tmpLat = $arrFromCoord[0][0];
            $tmpLong = $arrFromCoord[0][1];
                      
            $approxDistance = vincentyGreatCircleDistance($initialLat, $initialLong, $tmpLat, $tmpLong);            
            if($approxDistance/1000 < $distanceParameter) {
                $tmpNumberVehicles = ($trajet->full_load === 1) ? "FL" : $trajet->vans . "C";
                $tmpUsedCars = ($trajet->used_cars === 1) ? "UC" : "";
                $tmpMatch = $trajet->from_others . " -> " . $trajet->to_others . " - " . round($trajet->distance/1000, 1) ."Km | ". $tmpNumberVehicles . " " . $tmpUsedCars. " | " . round($approxDistance/1000, 2) . "Km";
                array_push($arrayRes, ["id" => $trajet->id, "label" => $tmpMatch]);
            }                                    
        }

        if(empty($arrayRes))
            return json_encode(array("error" => 1, "message" => "There are no match for this distance parameter."));  
      
        $lsMatches = "";
        foreach($arrayRes as $match) {
            $lsMatches .= "<button type='button' value='".$match['id']."' class='list-group-item list-group-item-action'>".$match['label']."</button>";
        }

        return json_encode(array(
            "error" => 0,          
            "lsMatches" => $lsMatches
        ));
    }
  
    /**
    * Update the selected elements with mutual id's, and returns status code.
    *
    * @param  Request $request
    * @return \Illuminate\Http\Response
    **/
    public function matchElements(Request $request)
    {      
        $idMatch = $request->currentMatch;
        $idElementMatched = $request->elementMatched;
        try { 
            $currentMatch = Trajet::findOrFail($idMatch);
            $elementMatched = Trajet::findOrFail($idElementMatched);          
            $currentMatch->matched_to = $idElementMatched;
            $elementMatched->matched_to = $idMatch;          
            $currentMatch->save();
            $elementMatched->save();          
            echo json_encode(array("error" => 0));
        } catch(ModelNotFoundException $e) {
            return json_encode(
                array(                    
                    "error" => 1,
                    "message" => "There has been an error during the match of these elements. Please reload the page and retry."
                )
            );
        }        
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
    * @return string $json
    */
    public function getRouteList() {
        
        $typeManager = getManagerType();        
        
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
            echo json_encode("There are currently no loads or trucks available.");  
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
            array_push($retArrFull, ["countryCode" => $countryDeparture, "label" => $displayText]);
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
            array_push($retArrPart, ["countryCode" => $countryDeparture, "label" => $displayText]);
        }
        
        // Ordering our return array in a ascendant way
        array_multisort($retArrFull, SORT_ASC);        
        array_multisort($retArrPart, SORT_ASC);        
        // dd($retArr);

        $finalStr = PHP_EOL.PHP_EOL."FULL LOAD".PHP_EOL.PHP_EOL;
        $tmp = "";
        foreach($retArrFull as $elem) {
          if($tmp != $elem['countryCode']) {
            $tmp = $elem['countryCode'];
            $finalStr .= PHP_EOL.PHP_EOL.$tmp.PHP_EOL.PHP_EOL;
          }
          $finalStr .= $elem['label'].PHP_EOL.PHP_EOL;
        }
        $finalStr .= PHP_EOL.PHP_EOL.PHP_EOL."PART LOAD".PHP_EOL.PHP_EOL;
        foreach($retArrPart as $elem) {
          if($tmp != $elem['countryCode']) {
            $tmp = $elem['countryCode'];
            $finalStr .= PHP_EOL.PHP_EOL.$tmp.PHP_EOL.PHP_EOL;
          }
          $finalStr .= $elem['label'].PHP_EOL.PHP_EOL;
        }

        echo json_encode($finalStr);    
        exit;  
  }

}
