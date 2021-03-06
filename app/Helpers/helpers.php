<?php

use App\Models\Agency;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use App\Models\Prospect;
use App\Models\Manager;
use App\Models\State;

if (!function_exists('str_contains')) {
    function str_contains (string $haystack, string $needle) {
        return empty($needle) || strpos($haystack, $needle) !== false;
    }
}

if (!function_exists("countryCodeToEmojiName")) {
    function countryCodeToEmojiName($countryCode) {
        switch ($countryCode) {
            case "AF":
                return "๐ฆ๐ซ Afghanistan";
            case "AX":
                return "๐ฆ๐ฝ รland Islands";
            case "AL":
                return "๐ฆ๐ฑ Albania";
            case "DZ":
                return "๐ฉ๐ฟ Algeria";
            case "AS":
                return "๐ฆ๐ธ American Samoa";
            case "AD":
                return "๐ฆ๐ฉ Andorra";
            case "AO":
                return "๐ฆ๐ด Angola ";
            case "AI":
                return "๐ฆ๐ฎ Anguilla";
            case "AQ":
                return "๐ฆ๐ถ Antarctica";
            case "AG":
                return "๐ฆ๐ฌ Antigua and Barbuda";
            case "AR":
                return "๐ฆ๐ท Argentina";
            case "AM":
                return "๐ฆ๐ฒ Armenia";
            case "AW":
                return "๐ฆ๐ผ Aruba";
            case "AU":
                return "๐ฆ๐บ Australia";
            case "AT":
                return "๐ฆ๐น Austria";
            case "AZ":
                return "๐ฆ๐ฟ Azerbaijan";
            case "BS":
                return "๐ง๐ธ Bahamas";
            case "BH":
                return "๐ง๐ญ Bahrain";
            case "BD":
                return "๐ง๐ฉ Bangladesh";
            case "BB":
                return "๐ง๐ง Barbados";
            case "BY":
                return "๐ง๐พ Belarus";
            case "BE":
                return "๐ง๐ช Belgium";
            case "BZ":
                return "๐ง๐ฟ Belize";
            case "BJ":
                return "๐ง๐ฏ Benin";
            case "BM":
                return "๐ง๐ฒ Bermuda";
            case "BT":
                return "๐ง๐น Bhutan";
            case "BO":
                return "๐ง๐ด Bolivia, Plurinational State of";
            case "BQ":
                return "๐ง๐ถ Bonaire, Sint Eustatius and Saba";
            case "BA":
                return "๐ง๐ฆ Bosnia and Herzegovina";
            case "BW":
                return "๐ง๐ผ Botswana";
            case "BV":
                return "๐ง๐ป Bouvet Island";
            case "BR":
                return "๐ง๐ท Brazil";
            case "IO":
                return "๐ฎ๐ด British Indian Ocean Territory";
            case "BN":
                return "๐ง๐ณ Brunei Darussalam";
            case "BG":
                return "๐ง๐ฌ Bulgaria";
            case "BF":
                return "๐ง๐ซ Burkina Faso";
            case "BI":
                return "๐ง๐ฎ Burundi";
            case "KH":
                return "๐ฐ๐ญ Cambodia";
            case "CM":
                return "๐จ๐ฒ Cameroon";
            case "CA":
                return "๐จ๐ฆ Canada";
            case "CV":
                return "๐จ๐ป Cape Verde";
            case "KY":
                return "๐ฐ๐พ Cayman Islands";
            case "CF":
                return "๐จ๐ซ Central African Republic";
            case "TD":
                return "๐น๐ฉ Chad";
            case "CL":
                return "๐จ๐ฑ Chile";
            case "CN":
                return "๐จ๐ณ China";
            case "CX":
                return "๐จ๐ฝ Christmas Island";
            case "CC":
                return "๐จ๐จ Cocos (Keeling) Islands";
            case "CO":
                return "๐จ๐ด Colombia";
            case "KM":
                return "๐ฐ๐ฒ Comoros";
            case "CG":
                return "๐จ๐ฌ Congo";
            case "CD":
                return "๐จ๐ฉ Congo, the Democratic Republic of the";
            case "CK":
                return "๐จ๐ฐ Cook Islands";
            case "CR":
                return "๐จ๐ท Costa Rica";
            case "CI":
                return "๐จ๐ฎ Cรดte d\"Ivoire";
            case "HR":
                return "๐ญ๐ท Croatia";
            case "CU":
                return "๐จ๐บ Cuba";
            case "CW":
                return "๐จ๐ผ Curaรงao";
            case "CY":
                return "๐จ๐พ Cyprus";
            case "CZ":
                return "๐จ๐ฟ Czech Republic";
            case "DK":
                return "๐ฉ๐ฐ Denmark";
            case "DJ":
                return "๐ฉ๐ฏ Djibouti";
            case "DM":
                return "๐ฉ๐ฒ Dominica";
            case "DO":
                return "๐ฉ๐ด Dominican Republic";
            case "EC":
                return "๐ช๐จ Ecuador";
            case "EG":
                return "๐ช๐ฌ Egypt";
            case "SV":
                return "๐ธ๐ป El Salvador";
            case "GQ":
                return "๐ฌ๐ถ Equatorial Guinea";
            case "ER":
                return "๐ช๐ท Eritrea";
            case "EE":
                return "๐ช๐ช Estonia";
            case "ET":
                return "๐ช๐น Ethiopia";
            case "FK":
                return "๐ซ๐ฐ Falkland Islands (Malvinas)";
            case "FO":
                return "๐ซ๐ด Faroe Islands";
            case "FJ":
                return "๐ซ๐ฏ Fiji";
            case "FI":
                return "๐ซ๐ฎ Finland";
            case "FR":
                return "๐ซ๐ท France";
            case "GF":
                return "๐ฌ๐ซ French Guiana";
            case "PF":
                return "๐ต๐ซ French Polynesia";
            case "TF":
                return "๐น๐ซ French Southern Territories";
            case "GA":
                return "๐ฌ๐ฆ Gabon";
            case "GM":
                return "๐ฌ๐ฒ Gambia";
            case "GE":
                return "๐ฌ๐ช Georgia";
            case "DE":
                return "๐ฉ๐ช Germany";
            case "GH":
                return "๐ฌ๐ญ Ghana";
            case "GI":
                return "๐ฌ๐ฎ Gibraltar";
            case "GR":
                return "๐ฌ๐ท Greece";
            case "GL":
                return "๐ฌ๐ฑ Greenland";
            case "GD":
                return "๐ฌ๐ฉ Grenada";
            case "GP":
                return "๐ฌ๐ต Guadeloupe";
            case "GU":
                return "๐ฌ๐บ Guam";
            case "GT":
                return "๐ฌ๐น Guatemala";
            case "GG":
                return "๐ฌ๐ฌ Guernsey ";
            case "GN":
                return "๐ฌ๐ณ Guinea";
            case "GW":
                return "๐ฌ๐ผ Guinea-Bissau";
            case "GY":
                return "๐ฌ๐พ Guyana";
            case "HT":
                return "๐ญ๐น Haiti";
            case "HM":
                return "๐ญ๐ฒ Heard Island and McDonald Islands";
            case "VA":
                return "๐ป๐ฆ Holy See (Vatican City State)";
            case "HN":
                return "๐ญ๐ณ Honduras";
            case "HK":
                return "๐ญ๐ฐ Hong Kong";
            case "HU":
                return "๐ญ๐บ Hungary";
            case "IS":
                return "๐ฎ๐ธ Iceland";
            case "IN":
                return "๐ฎ๐ณ India";
            case "ID":
                return "๐ฎ๐ฉ Indonesia";
            case "IR":
                return "๐ฎ๐ท Iran, Islamic Republic of";
            case "IQ":
                return "๐ฎ๐ถ Iraq";
            case "IE":
                return "๐ฎ๐ช Ireland";
            case "IM":
                return "๐ฎ๐ฒ Isle of Man";
            case "IL":
                return "๐ฎ๐ฑ Israel";
            case "IT":
                return "๐ฎ๐น Italy";
            case "JM":
                return "๐ฏ๐ฒ Jamaica";
            case "JP":
                return "๐ฏ๐ต Japan";
            case "JE":
                return "๐ฏ๐ช Jersey";
            case "JO":
                return "๐ฏ๐ด Jordan";
            case "KZ":
                return "๐ฐ๐ฟ Kazakhstan";
            case "KE":
                return "๐ฐ๐ช Kenya";
            case "KI":
                return "๐ฐ๐ฎ Kiribati";
            case "KP":
                return "๐ฐ๐ต Korea, Democratic People\"s Republic of";
            case "KR":
                return "๐ฐ๐ท Korea, Republic of";
            case "KW":
                return "๐ฐ๐ผ Kuwait";
            case "KG":
                return "๐ฐ๐ฌ Kyrgyzstan";
            case "LA":
                return "๐ฑ๐ฆ Lao People\"s Democratic Republic ";
            case "LV":
                return "๐ฑ๐ป Latvia";
            case "LB":
                return "๐ฑ๐ง Lebanon";
            case "LS":
                return "๐ฑ๐ธ Lesotho";
            case "LR":
                return "๐ฑ๐ท Liberia";
            case "LY":
                return "๐ฑ๐พ Libya";
            case "LI":
                return "๐ฑ๐ฎ Liechtenstein";
            case "LT":
                return "๐ฑ๐น Lithuania";
            case "LU":
                return "๐ฑ๐บ Luxembourg";
            case "MO":
                return "๐ฒ๐ด Macao";
            case "MK":
                return "๐ฒ๐ฐ Macedonia, the former Yugoslav Republic of";
            case "MG":
                return "๐ฒ๐ฌ Madagascar";
            case "MW":
                return "๐ฒ๐ผ Malawi";
            case "MY":
                return "๐ฒ๐พ Malaysia";
            case "MV":
                return "๐ฒ๐ป Maldives";
            case "ML":
                return "๐ฒ๐ฑ Mali";
            case "MT":
                return "๐ฒ๐น Malta";
            case "MH":
                return "๐ฒ๐ญ Marshall Islands";
            case "MQ":
                return "๐ฒ๐ถ Martinique";
            case "MR":
                return "๐ฒ๐ท Mauritania";
            case "MU":
                return "๐ฒ๐บ Mauritius";
            case "YT":
                return "๐พ๐น Mayotte";
            case "MX":
                return "๐ฒ๐ฝ Mexico";
            case "FM":
                return "๐ซ๐ฒ Micronesia, Federated States of";
            case "MD":
                return "๐ฒ๐ฉ Moldova, Republic of";
            case "MC":
                return "๐ฒ๐จ Monaco";
            case "MN":
                return "๐ฒ๐ณ Mongolia";
            case "ME":
                return "๐ฒ๐ช Montenegro";
            case "MS":
                return "๐ฒ๐ธ Montserrat";
            case "MA":
                return "๐ฒ๐ฆ Morocco";
            case "MZ":
                return "๐ฒ๐ฟ Mozambique";
            case "MM":
                return "๐ฒ๐ฒ Myanmar";
            case "NA":
                return "๐ณ๐ฆ Namibia";
            case "NR":
                return "๐ณ๐ท Nauru";
            case "NP":
                return "๐ณ๐ต Nepal";
            case "NL":
                return "๐ณ๐ฑ Netherlands";
            case "NC":
                return "๐ณ๐จ New Caledonia";
            case "NZ":
                return "๐ณ๐ฟ New Zealand";
            case "NI":
                return "๐ณ๐ฎ Nicaragua";
            case "NE":
                return "๐ณ๐ช Niger";
            case "NG":
                return "๐ณ๐ฌ Nigeria";
            case "NU":
                return "๐ณ๐บ Niue";
            case "NF":
                return "๐ณ๐ซ Norfolk Island";
            case "MP":
                return "๐ฒ๐ต Northern Mariana Islands";
            case "NO":
                return "๐ณ๐ด Norway";
            case "OM":
                return "๐ด๐ฒ Oman";
            case "PK":
                return "๐ต๐ฐ Pakistan";
            case "PW":
                return "๐ต๐ผ Palau";
            case "PS":
                return "๐ต๐ธ Palestinian Territory, Occupied";
            case "PA":
                return "๐ต๐ฆ Panama";
            case "PG":
                return "๐ต๐ฌ Papua New Guinea";
            case "PY":
                return "๐ต๐พ Paraguay";
            case "PE":
                return "๐ต๐ช Peru";
            case "PH":
                return "๐ต๐ญ Philippines";
            case "PN":
                return "๐ต๐ณ Pitcairn ";
            case "PL":
                return "๐ต๐ฑ Poland";
            case "PT":
                return "๐ต๐น Portugal";
            case "PR":
                return "๐ต๐ท Puerto Rico";
            case "QA":
                return "๐ถ๐ฆ Qatar";
            case "RE":
                return "๐ท๐ช Rรฉunion";
            case "RO":
                return "๐ท๐ด Romania";
            case "RU":
                return "๐ท๐บ Russian Federation";
            case "RW":
                return "๐ท๐ผ Rwanda";
            case "BL":
                return "๐ง๐ฑ Saint Barthรฉlemy";
            case "SH":
                return "๐ธ๐ญ Saint Helena, Ascension and Tristan da Cunha";
            case "KN":
                return "๐ฐ๐ณ Saint Kitts and Nevis";
            case "LC":
                return "๐ฑ๐จ Saint Lucia";
            case "MF":
                return "๐ฒ๐ซ Saint Martin (French part)";
            case "PM":
                return "๐ต๐ฒ Saint Pierre and Miquelon";
            case "VC":
                return "๐ป๐จ Saint Vincent and the Grenadines";
            case "WS":
                return "๐ผ๐ธ Samoa";
            case "SM":
                return "๐ธ๐ฒ San Marino";
            case "ST":
                return "๐ธ๐น Sao Tome and Principe";
            case "SA":
                return "๐ธ๐ฆ Saudi Arabia";
            case "SN":
                return "๐ธ๐ณ Senegal";
            case "RS":
                return "๐ท๐ธ Serbia";
            case "SC":
                return "๐ธ๐จ Seychelles";
            case "SL":
                return "๐ธ๐ฑ Sierra Leone";
            case "SG":
                return "๐ธ๐ฌ Singapore";
            case "SX":
                return "๐ธ๐ฝ Sint Maarten (Dutch part)";
            case "SK":
                return "๐ธ๐ฐ Slovakia";
            case "SI":
                return "๐ธ๐ฎ Slovenia";
            case "SB":
                return "๐ธ๐ง Solomon Islands";
            case "SO":
                return "๐ธ๐ด Somalia";
            case "ZA":
                return "๐ฟ๐ฆ South Africa";
            case "GS":
                return "๐ฌ๐ธ South Georgia and the South Sandwich Islands";
            case "SS":
                return "๐ธ๐ธ South Sudan";
            case "ES":
                return "๐ช๐ธ Spain";
            case "LK":
                return "๐ฑ๐ฐ Sri Lanka";
            case "SD":
                return "๐ธ๐ฉ Sudan";
            case "SR":
                return "๐ธ๐ท Suriname";
            case "SJ":
                return "๐ธ๐ฏ Svalbard and Jan Mayen";
            case "SZ":
                return "๐ธ๐ฟ Swaziland";
            case "SE":
                return "๐ธ๐ช Sweden";
            case "CH":
                return "๐จ๐ญ Switzerland";
            case "SY":
                return "๐ธ๐พ Syrian Arab Republic";
            case "TW":
                return "๐น๐ผ Taiwan, Province of China";
            case "TJ":
                return "๐น๐ฏ Tajikistan";
            case "TZ":
                return "๐น๐ฟ Tanzania, United Republic of";
            case "TH":
                return "๐น๐ญ Thailand";
            case "TL":
                return "๐น๐ฑ Timor-Leste";
            case "TG":
                return "๐น๐ฌ Togo";
            case "TK":
                return "๐น๐ฐ Tokelau";
            case "TO":
                return "๐น๐ด Tonga";
            case "TT":
                return "๐น๐น Trinidad and Tobago";
            case "TN":
                return "๐น๐ณ Tunisia";
            case "TR":
                return "๐น๐ท Turkey";
            case "TM":
                return "๐น๐ฒ Turkmenistan";
            case "TC":
                return "๐น๐จ Turks and Caicos Islands";
            case "TV":
                return "๐น๐ป Tuvalu";
            case "UG":
                return "๐บ๐ฌ Uganda";
            case "UA":
                return "๐บ๐ฆ Ukraine";
            case "AE":
                return "๐ฆ๐ช United Arab Emirates";
            case "GB":
                return "๐ฌ๐ง United Kingdom";
            case "US":
                return "๐บ๐ธ United States";
            case "UM":
                return "๐บ๐ฒ United States Minor Outlying Islands";
            case "UY":
                return "๐บ๐พ Uruguay";
            case "UZ":
                return "๐บ๐ฟ Uzbekistan";
            case "VU":
                return "๐ป๐บ Vanuatu";
            case "VE":
                return "๐ป๐ช Venezuela, Bolivarian Republic of";
            case "VN":
                return "๐ป๐ณ Viet Nam";
            case "VG":
                return "๐ป๐ฌ Virgin Islands, British";
            case "VI":
                return "๐ป๐ฎ Virgin Islands, U.S.";
            case "WF":
                return "๐ผ๐ซ Wallis and Futuna";
            case "EH":
                return "๐ช๐ญ Western Sahara";
            case "YE":
                return "๐พ๐ช Yemen";
            case "ZM":
                return "๐ฟ๐ฒ Zambia";
            case "ZW":
                return "๐ฟ๐ผ Zimbabwe";
            default:
                return "โ Unknown country";
        }
    }
}

if(!function_exists("boolToHuman")) {
    function boolToHuman($bool) {
        switch($bool) {
            case 0:
            case false:
                return "No";
            case 1:
            case true:
                return "Yes";
            default :
                return "N/A";
        }
    }
}
if(!function_exists("getallmanagers")) {
    function getallmanagers() {
        $results = DB::select('select id , first_name, last_name , type from managers'); 
        return $results;
    }
}

if(!function_exists("getManagerName")) {
    function getManagerName($managerId, $param) {
        if($param === "complete") {
          $manager = Manager::findOrFail($managerId);
          $agency = Agency::findOrFail($manager->agency_id);
          return $manager->first_name ." ". $manager->last_name ." | ". $agency->agency_name;
        }  
        $results = DB::select('select first_name, last_name from managers where id = :id', ['id' => $managerId]); 
        if (empty($results))
            return "N/A";
        foreach ($results as $result) {
            if($param == "all")
                return $result->first_name . " " . $result->last_name;
            return $result->first_name;
        }
    }
}

// Weak method, should use better prog elsewhere
if(!function_exists("getProspectById")) {
    function getProspectById($id) {
        return Prospect::findOrFail($id);        
    }
}

if(!function_exists("getManagerType")) {
    function getManagerType() {
        $managerType = App\Models\Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["type"];
        return $managerType;   
    }
}

if(!function_exists("getManagerEmail")) {
    function getManagerEmail() {
        $managerType = Auth::user()->email;
        return $managerType;   
    }
}

if(!function_exists("getStateToHuman")) {
    function getStateToHuman($state_id) {
        $state = State::findOrFail($state_id);
        return $state->short_desc;   
    }
}

if(!function_exists("getManagerId")) {
    function getManagerId() {
        $manager_id = App\Models\Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["id"];
        return $manager_id;   
    }
}

if(!function_exists("countryToHuman")) {
    function countryToHuman($code) {
      $country = Country::where("code", "=", $code)->firstOrFail();
      $humanReadable = $country->emoji . " " . $country->shortname;
      return $humanReadable;
    }
}

if(!function_exists("codeToCountry")) {
  function codeToCountry($code) {
    $country = Country::where("code", "=", $code)->firstOrFail();
    return $country->shortname;
  }
}

if(!function_exists("getPhoneCode")) {
    function getPhoneCode($countryCode) {
        $country = Country::where("code", "=", $countryCode)->firstOrFail();
        $phoneCode = $country->phone_code;
        return $phoneCode;
    }
}

if (!function_exists("countryCodeExtractor")) 
{
    function countryCodeExtractor($placeStr)
    {
        // Check if the FROM is defined, else continue
        if($placeStr === null)
          return;

        // If it's a load with multiple loading places, substr the first loading place (before '+')
        if(str_contains($placeStr, "+")) {
            $tmp = explode("+", $placeStr);
            $placeStr = $tmp[0];
        } 

        // Find the position of '(' and substr the right part
        $tmp = explode("(", $placeStr);   	
        $secondSub = $tmp[1];

        // Then Find the position of ')' and substr the left part
        $tmp = explode(")", $secondSub);

        $result = $tmp[0];
        return $result;
    }
}

/**
 * Calculates the great-circle distance between two points, with
 * the Haversine formula.
 * @param float $latitudeFrom Latitude of start point in [deg decimal]
 * @param float $longitudeFrom Longitude of start point in [deg decimal]
 * @param float $latitudeTo Latitude of target point in [deg decimal]
 * @param float $longitudeTo Longitude of target point in [deg decimal]
 * @param float $earthRadius Mean earth radius in [m]
 * @return float Distance between points in [m] (same as earthRadius)
 */
if (!function_exists("vincentyGreatCircleDistance")) {
    function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // Convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
          pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
}

if(!function_exists("countryCodeToCallingCode")) {
    function countryCodeToCallingCode($countryCode) {
        switch ($countryCode) {
            case "AF":
                return "93";
            case "AX":
                return "358";
            case "AL":
                return "355";
            case "DZ":
                return "213";
            case "AS":
                return "1684";
            case "AD":
                return "376";
            case "AO":
                return "244";
            case "AI":
                return "1264";
            case "AQ":
                return "672";
            case "AG":
                return "1268";
            case "AR":
                return "54";
            case "AM":
                return "374";
            case "AW":
                return "297";
            case "AU":
                return "61";
            case "AT":
                return "43";
            case "AZ":
                return "994";
            case "BS":
                return "1242";
            case "BH":
                return "973";
            case "BD":
                return "880";
            case "BB":
                return "1246";
            case "BY":
                return "375";
            case "BE":
                return "32";
            case "BZ":
                return "501";
            case "BJ":
                return "229";
            case "BM":
                return "1441";
            case "BT":
                return "975";
            case "BO":
                return "591";
            case "BQ":
                return "599";
            case "BA":
                return "387";
            case "BW":
                return "267";
            case "BV":
                return "47";
            case "BR":
                return "55";
            case "IO":
                return "246";
            case "BN":
                return "673";
            case "BG":
                return "359";
            case "BF":
                return "226";
            case "BI":
                return "257";
            case "KH":
                return "855";
            case "CM":
                return "237";
            case "CA":
                return "1";
            case "CV":
                return "238";
            case "KY":
                return "1345";
            case "CF":
                return "236";
            case "TD":
                return "235";
            case "CL":
                return "56";
            case "CN":
                return "86";
            case "CX":
                return "61";
            case "CC":
                return "61";
            case "CO":
                return "57";
            case "KM":
                return "269";
            case "CG":
                return "242";
            case "CD":
                return "243";
            case "CK":
                return "682";
            case "CR":
                return "506";
            case "CI":
                return "225";
            case "HR":
                return "385";
            case "CU":
                return "53";
            case "CW":
                return "599";
            case "CY":
                return "357";
            case "CZ":
                return "420";
            case "DK":
                return "45";
            case "DJ":
                return "253";
            case "DM":
                return "1767";
            case "DO":
                return "1809";
            case "EC":
                return "593";
            case "EG":
                return "20";
            case "SV":
                return "503";
            case "GQ":
                return "240";
            case "ER":
                return "291";
            case "EE":
                return "372";
            case "ET":
                return "251";
            case "FK":
                return "500";
            case "FO":
                return "298";
            case "FJ":
                return "679";
            case "FI":
                return "358";
            case "FR":
                return "33";
            case "GF":
                return "594";
            case "PF":
                return "689";
            case "TF":
                return "262";
            case "GA":
                return "241";
            case "GM":
                return "220";
            case "GE":
                return "995";
            case "DE":
                return "49";
            case "GH":
                return "233";
            case "GI":
                return "350";
            case "GR":
                return "30";
            case "GL":
                return "299";
            case "GD":
                return "1473";
            case "GP":
                return "590";
            case "GU":
                return "1671";
            case "GT":
                return "502";
            case "GG":
                return "44";
            case "GN":
                return "224";
            case "GW":
                return "245";
            case "GY":
                return "592";
            case "HT":
                return "509";
            case "HM":
                return "672";
            case "VA":
                return "379";
            case "HN":
                return "504";
            case "HK":
                return "852";
            case "HU":
                return "36";
            case "IS":
                return "354";
            case "IN":
                return "91";
            case "ID":
                return "62";
            case "IR":
                return "98";
            case "IQ":
                return "964";
            case "IE":
                return "353";
            case "IM":
                return "44";
            case "IL":
                return "972";
            case "IT":
                return "39";
            case "JM":
                return "1876";
            case "JP":
                return "81";
            case "JE":
                return "44";
            case "JO":
                return "962";
            case "KZ":
                return "7";
            case "KE":
                return "254";
            case "KI":
                return "686";
            case "KP":
                return "850";
            case "KR":
                return "82";
            case "KW":
                return "965";
            case "KG":
                return "996";
            case "LA":
                return "856";
            case "LV":
                return "371";
            case "LB":
                return "961";
            case "LS":
                return "266";
            case "LR":
                return "231";
            case "LY":
                return "218";
            case "LI":
                return "423";
            case "LT":
                return "370";
            case "LU":
                return "352";
            case "MO":
                return "853";
            case "MK":
                return "389";
            case "MG":
                return "261";
            case "MW":
                return "265";
            case "MY":
                return "60";
            case "MV":
                return "960";
            case "ML":
                return "223";
            case "MT":
                return "356";
            case "MH":
                return "692";
            case "MQ":
                return "596";
            case "MR":
                return "222";
            case "MU":
                return "230";
            case "YT":
                return "262";
            case "MX":
                return "52";
            case "FM":
                return "691";
            case "MD":
                return "373";
            case "MC":
                return "377";
            case "MN":
                return "976";
            case "ME":
                return "382";
            case "MS":
                return "1664";
            case "MA":
                return "212";
            case "MZ":
                return "258";
            case "MM":
                return "95";
            case "NA":
                return "264";
            case "NR":
                return "674";
            case "NP":
                return "977";
            case "NL":
                return "31";
            case "NC":
                return "687";
            case "NZ":
                return "64";
            case "NI":
                return "505";
            case "NE":
                return "227";
            case "NG":
                return "234";
            case "NU":
                return "683";
            case "NF":
                return "6723";
            case "MP":
                return "1670";
            case "NO":
                return "47";
            case "OM":
                return "968";
            case "PK":
                return "92";
            case "PW":
                return "680";
            case "PS":
                return "970";
            case "PA":
                return "507";
            case "PG":
                return "675";
            case "PY":
                return "595";
            case "PE":
                return "51";
            case "PH":
                return "63";
            case "PN":
                return "64";
            case "PL":
                return "48";
            case "PT":
                return "351";
            case "PR":
                return "1787";
            case "QA":
                return "974";
            case "RE":
                return "262";
            case "RO":
                return "40";
            case "RU":
                return "7";
            case "RW":
                return "250";
            case "BL":
                return "590";
            case "SH":
                return "290";
            case "KN":
                return "1869";
            case "LC":
                return "1758";
            case "MF":
                return "590";
            case "PM":
                return "508";
            case "VC":
                return "1784";
            case "WS":
                return "685";
            case "SM":
                return "378";
            case "ST":
                return "239";
            case "SA":
                return "966";
            case "SN":
                return "221";
            case "RS":
                return "381";
            case "SC":
                return "248";
            case "SL":
                return "232";
            case "SG":
                return "65";
            case "SX":
                return "1721";
            case "SK":
                return "421";
            case "SI":
                return "386";
            case "SB":
                return "677";
            case "SO":
                return "252";
            case "ZA":
                return "27";
            case "GS":
                return "500";
            case "SS":
                return "211";
            case "ES":
                return "34";
            case "LK":
                return "94";
            case "SD":
                return "249";
            case "SR":
                return "597";
            case "SJ":
                return "47";
            case "SZ":
                return "268";
            case "SE":
                return "46";
            case "CH":
                return "41";
            case "SY":
                return "963";
            case "TW":
                return "886";
            case "TJ":
                return "992";
            case "TZ":
                return "255";
            case "TH":
                return "66";
            case "TL":
                return "670";
            case "TG":
                return "228";
            case "TK":
                return "690";
            case "TO":
                return "676";
            case "TT":
                return "1868";
            case "TN":
                return "216";
            case "TR":
                return "90";
            case "TM":
                return "993";
            case "TC":
                return "1649";
            case "TV":
                return "688";
            case "UG":
                return "256";
            case "UA":
                return "380";
            case "AE":
                return "971";
            case "GB":
                return "44";
            case "US":
                return "1";
            case "UM":
                return "246";
            case "UY":
                return "598";
            case "UZ":
                return "998";
            case "VU":
                return "678";
            case "VE":
                return "58";
            case "VN":
                return "84";
            case "VG":
                return "1284";
            case "VI":
                return "1340";
            case "WF":
                return "681";
            case "EH":
                return "212";
            case "YE":
                return "967";
            case "ZM":
                return "260";
            case "ZW":
                return "263";
            default:
                return "n.a";
        }
    }
}

?>