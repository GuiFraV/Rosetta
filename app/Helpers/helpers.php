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
                return "🇦🇫 Afghanistan";
            case "AX":
                return "🇦🇽 Åland Islands";
            case "AL":
                return "🇦🇱 Albania";
            case "DZ":
                return "🇩🇿 Algeria";
            case "AS":
                return "🇦🇸 American Samoa";
            case "AD":
                return "🇦🇩 Andorra";
            case "AO":
                return "🇦🇴 Angola ";
            case "AI":
                return "🇦🇮 Anguilla";
            case "AQ":
                return "🇦🇶 Antarctica";
            case "AG":
                return "🇦🇬 Antigua and Barbuda";
            case "AR":
                return "🇦🇷 Argentina";
            case "AM":
                return "🇦🇲 Armenia";
            case "AW":
                return "🇦🇼 Aruba";
            case "AU":
                return "🇦🇺 Australia";
            case "AT":
                return "🇦🇹 Austria";
            case "AZ":
                return "🇦🇿 Azerbaijan";
            case "BS":
                return "🇧🇸 Bahamas";
            case "BH":
                return "🇧🇭 Bahrain";
            case "BD":
                return "🇧🇩 Bangladesh";
            case "BB":
                return "🇧🇧 Barbados";
            case "BY":
                return "🇧🇾 Belarus";
            case "BE":
                return "🇧🇪 Belgium";
            case "BZ":
                return "🇧🇿 Belize";
            case "BJ":
                return "🇧🇯 Benin";
            case "BM":
                return "🇧🇲 Bermuda";
            case "BT":
                return "🇧🇹 Bhutan";
            case "BO":
                return "🇧🇴 Bolivia, Plurinational State of";
            case "BQ":
                return "🇧🇶 Bonaire, Sint Eustatius and Saba";
            case "BA":
                return "🇧🇦 Bosnia and Herzegovina";
            case "BW":
                return "🇧🇼 Botswana";
            case "BV":
                return "🇧🇻 Bouvet Island";
            case "BR":
                return "🇧🇷 Brazil";
            case "IO":
                return "🇮🇴 British Indian Ocean Territory";
            case "BN":
                return "🇧🇳 Brunei Darussalam";
            case "BG":
                return "🇧🇬 Bulgaria";
            case "BF":
                return "🇧🇫 Burkina Faso";
            case "BI":
                return "🇧🇮 Burundi";
            case "KH":
                return "🇰🇭 Cambodia";
            case "CM":
                return "🇨🇲 Cameroon";
            case "CA":
                return "🇨🇦 Canada";
            case "CV":
                return "🇨🇻 Cape Verde";
            case "KY":
                return "🇰🇾 Cayman Islands";
            case "CF":
                return "🇨🇫 Central African Republic";
            case "TD":
                return "🇹🇩 Chad";
            case "CL":
                return "🇨🇱 Chile";
            case "CN":
                return "🇨🇳 China";
            case "CX":
                return "🇨🇽 Christmas Island";
            case "CC":
                return "🇨🇨 Cocos (Keeling) Islands";
            case "CO":
                return "🇨🇴 Colombia";
            case "KM":
                return "🇰🇲 Comoros";
            case "CG":
                return "🇨🇬 Congo";
            case "CD":
                return "🇨🇩 Congo, the Democratic Republic of the";
            case "CK":
                return "🇨🇰 Cook Islands";
            case "CR":
                return "🇨🇷 Costa Rica";
            case "CI":
                return "🇨🇮 Côte d\"Ivoire";
            case "HR":
                return "🇭🇷 Croatia";
            case "CU":
                return "🇨🇺 Cuba";
            case "CW":
                return "🇨🇼 Curaçao";
            case "CY":
                return "🇨🇾 Cyprus";
            case "CZ":
                return "🇨🇿 Czech Republic";
            case "DK":
                return "🇩🇰 Denmark";
            case "DJ":
                return "🇩🇯 Djibouti";
            case "DM":
                return "🇩🇲 Dominica";
            case "DO":
                return "🇩🇴 Dominican Republic";
            case "EC":
                return "🇪🇨 Ecuador";
            case "EG":
                return "🇪🇬 Egypt";
            case "SV":
                return "🇸🇻 El Salvador";
            case "GQ":
                return "🇬🇶 Equatorial Guinea";
            case "ER":
                return "🇪🇷 Eritrea";
            case "EE":
                return "🇪🇪 Estonia";
            case "ET":
                return "🇪🇹 Ethiopia";
            case "FK":
                return "🇫🇰 Falkland Islands (Malvinas)";
            case "FO":
                return "🇫🇴 Faroe Islands";
            case "FJ":
                return "🇫🇯 Fiji";
            case "FI":
                return "🇫🇮 Finland";
            case "FR":
                return "🇫🇷 France";
            case "GF":
                return "🇬🇫 French Guiana";
            case "PF":
                return "🇵🇫 French Polynesia";
            case "TF":
                return "🇹🇫 French Southern Territories";
            case "GA":
                return "🇬🇦 Gabon";
            case "GM":
                return "🇬🇲 Gambia";
            case "GE":
                return "🇬🇪 Georgia";
            case "DE":
                return "🇩🇪 Germany";
            case "GH":
                return "🇬🇭 Ghana";
            case "GI":
                return "🇬🇮 Gibraltar";
            case "GR":
                return "🇬🇷 Greece";
            case "GL":
                return "🇬🇱 Greenland";
            case "GD":
                return "🇬🇩 Grenada";
            case "GP":
                return "🇬🇵 Guadeloupe";
            case "GU":
                return "🇬🇺 Guam";
            case "GT":
                return "🇬🇹 Guatemala";
            case "GG":
                return "🇬🇬 Guernsey ";
            case "GN":
                return "🇬🇳 Guinea";
            case "GW":
                return "🇬🇼 Guinea-Bissau";
            case "GY":
                return "🇬🇾 Guyana";
            case "HT":
                return "🇭🇹 Haiti";
            case "HM":
                return "🇭🇲 Heard Island and McDonald Islands";
            case "VA":
                return "🇻🇦 Holy See (Vatican City State)";
            case "HN":
                return "🇭🇳 Honduras";
            case "HK":
                return "🇭🇰 Hong Kong";
            case "HU":
                return "🇭🇺 Hungary";
            case "IS":
                return "🇮🇸 Iceland";
            case "IN":
                return "🇮🇳 India";
            case "ID":
                return "🇮🇩 Indonesia";
            case "IR":
                return "🇮🇷 Iran, Islamic Republic of";
            case "IQ":
                return "🇮🇶 Iraq";
            case "IE":
                return "🇮🇪 Ireland";
            case "IM":
                return "🇮🇲 Isle of Man";
            case "IL":
                return "🇮🇱 Israel";
            case "IT":
                return "🇮🇹 Italy";
            case "JM":
                return "🇯🇲 Jamaica";
            case "JP":
                return "🇯🇵 Japan";
            case "JE":
                return "🇯🇪 Jersey";
            case "JO":
                return "🇯🇴 Jordan";
            case "KZ":
                return "🇰🇿 Kazakhstan";
            case "KE":
                return "🇰🇪 Kenya";
            case "KI":
                return "🇰🇮 Kiribati";
            case "KP":
                return "🇰🇵 Korea, Democratic People\"s Republic of";
            case "KR":
                return "🇰🇷 Korea, Republic of";
            case "KW":
                return "🇰🇼 Kuwait";
            case "KG":
                return "🇰🇬 Kyrgyzstan";
            case "LA":
                return "🇱🇦 Lao People\"s Democratic Republic ";
            case "LV":
                return "🇱🇻 Latvia";
            case "LB":
                return "🇱🇧 Lebanon";
            case "LS":
                return "🇱🇸 Lesotho";
            case "LR":
                return "🇱🇷 Liberia";
            case "LY":
                return "🇱🇾 Libya";
            case "LI":
                return "🇱🇮 Liechtenstein";
            case "LT":
                return "🇱🇹 Lithuania";
            case "LU":
                return "🇱🇺 Luxembourg";
            case "MO":
                return "🇲🇴 Macao";
            case "MK":
                return "🇲🇰 Macedonia, the former Yugoslav Republic of";
            case "MG":
                return "🇲🇬 Madagascar";
            case "MW":
                return "🇲🇼 Malawi";
            case "MY":
                return "🇲🇾 Malaysia";
            case "MV":
                return "🇲🇻 Maldives";
            case "ML":
                return "🇲🇱 Mali";
            case "MT":
                return "🇲🇹 Malta";
            case "MH":
                return "🇲🇭 Marshall Islands";
            case "MQ":
                return "🇲🇶 Martinique";
            case "MR":
                return "🇲🇷 Mauritania";
            case "MU":
                return "🇲🇺 Mauritius";
            case "YT":
                return "🇾🇹 Mayotte";
            case "MX":
                return "🇲🇽 Mexico";
            case "FM":
                return "🇫🇲 Micronesia, Federated States of";
            case "MD":
                return "🇲🇩 Moldova, Republic of";
            case "MC":
                return "🇲🇨 Monaco";
            case "MN":
                return "🇲🇳 Mongolia";
            case "ME":
                return "🇲🇪 Montenegro";
            case "MS":
                return "🇲🇸 Montserrat";
            case "MA":
                return "🇲🇦 Morocco";
            case "MZ":
                return "🇲🇿 Mozambique";
            case "MM":
                return "🇲🇲 Myanmar";
            case "NA":
                return "🇳🇦 Namibia";
            case "NR":
                return "🇳🇷 Nauru";
            case "NP":
                return "🇳🇵 Nepal";
            case "NL":
                return "🇳🇱 Netherlands";
            case "NC":
                return "🇳🇨 New Caledonia";
            case "NZ":
                return "🇳🇿 New Zealand";
            case "NI":
                return "🇳🇮 Nicaragua";
            case "NE":
                return "🇳🇪 Niger";
            case "NG":
                return "🇳🇬 Nigeria";
            case "NU":
                return "🇳🇺 Niue";
            case "NF":
                return "🇳🇫 Norfolk Island";
            case "MP":
                return "🇲🇵 Northern Mariana Islands";
            case "NO":
                return "🇳🇴 Norway";
            case "OM":
                return "🇴🇲 Oman";
            case "PK":
                return "🇵🇰 Pakistan";
            case "PW":
                return "🇵🇼 Palau";
            case "PS":
                return "🇵🇸 Palestinian Territory, Occupied";
            case "PA":
                return "🇵🇦 Panama";
            case "PG":
                return "🇵🇬 Papua New Guinea";
            case "PY":
                return "🇵🇾 Paraguay";
            case "PE":
                return "🇵🇪 Peru";
            case "PH":
                return "🇵🇭 Philippines";
            case "PN":
                return "🇵🇳 Pitcairn ";
            case "PL":
                return "🇵🇱 Poland";
            case "PT":
                return "🇵🇹 Portugal";
            case "PR":
                return "🇵🇷 Puerto Rico";
            case "QA":
                return "🇶🇦 Qatar";
            case "RE":
                return "🇷🇪 Réunion";
            case "RO":
                return "🇷🇴 Romania";
            case "RU":
                return "🇷🇺 Russian Federation";
            case "RW":
                return "🇷🇼 Rwanda";
            case "BL":
                return "🇧🇱 Saint Barthélemy";
            case "SH":
                return "🇸🇭 Saint Helena, Ascension and Tristan da Cunha";
            case "KN":
                return "🇰🇳 Saint Kitts and Nevis";
            case "LC":
                return "🇱🇨 Saint Lucia";
            case "MF":
                return "🇲🇫 Saint Martin (French part)";
            case "PM":
                return "🇵🇲 Saint Pierre and Miquelon";
            case "VC":
                return "🇻🇨 Saint Vincent and the Grenadines";
            case "WS":
                return "🇼🇸 Samoa";
            case "SM":
                return "🇸🇲 San Marino";
            case "ST":
                return "🇸🇹 Sao Tome and Principe";
            case "SA":
                return "🇸🇦 Saudi Arabia";
            case "SN":
                return "🇸🇳 Senegal";
            case "RS":
                return "🇷🇸 Serbia";
            case "SC":
                return "🇸🇨 Seychelles";
            case "SL":
                return "🇸🇱 Sierra Leone";
            case "SG":
                return "🇸🇬 Singapore";
            case "SX":
                return "🇸🇽 Sint Maarten (Dutch part)";
            case "SK":
                return "🇸🇰 Slovakia";
            case "SI":
                return "🇸🇮 Slovenia";
            case "SB":
                return "🇸🇧 Solomon Islands";
            case "SO":
                return "🇸🇴 Somalia";
            case "ZA":
                return "🇿🇦 South Africa";
            case "GS":
                return "🇬🇸 South Georgia and the South Sandwich Islands";
            case "SS":
                return "🇸🇸 South Sudan";
            case "ES":
                return "🇪🇸 Spain";
            case "LK":
                return "🇱🇰 Sri Lanka";
            case "SD":
                return "🇸🇩 Sudan";
            case "SR":
                return "🇸🇷 Suriname";
            case "SJ":
                return "🇸🇯 Svalbard and Jan Mayen";
            case "SZ":
                return "🇸🇿 Swaziland";
            case "SE":
                return "🇸🇪 Sweden";
            case "CH":
                return "🇨🇭 Switzerland";
            case "SY":
                return "🇸🇾 Syrian Arab Republic";
            case "TW":
                return "🇹🇼 Taiwan, Province of China";
            case "TJ":
                return "🇹🇯 Tajikistan";
            case "TZ":
                return "🇹🇿 Tanzania, United Republic of";
            case "TH":
                return "🇹🇭 Thailand";
            case "TL":
                return "🇹🇱 Timor-Leste";
            case "TG":
                return "🇹🇬 Togo";
            case "TK":
                return "🇹🇰 Tokelau";
            case "TO":
                return "🇹🇴 Tonga";
            case "TT":
                return "🇹🇹 Trinidad and Tobago";
            case "TN":
                return "🇹🇳 Tunisia";
            case "TR":
                return "🇹🇷 Turkey";
            case "TM":
                return "🇹🇲 Turkmenistan";
            case "TC":
                return "🇹🇨 Turks and Caicos Islands";
            case "TV":
                return "🇹🇻 Tuvalu";
            case "UG":
                return "🇺🇬 Uganda";
            case "UA":
                return "🇺🇦 Ukraine";
            case "AE":
                return "🇦🇪 United Arab Emirates";
            case "GB":
                return "🇬🇧 United Kingdom";
            case "US":
                return "🇺🇸 United States";
            case "UM":
                return "🇺🇲 United States Minor Outlying Islands";
            case "UY":
                return "🇺🇾 Uruguay";
            case "UZ":
                return "🇺🇿 Uzbekistan";
            case "VU":
                return "🇻🇺 Vanuatu";
            case "VE":
                return "🇻🇪 Venezuela, Bolivarian Republic of";
            case "VN":
                return "🇻🇳 Viet Nam";
            case "VG":
                return "🇻🇬 Virgin Islands, British";
            case "VI":
                return "🇻🇮 Virgin Islands, U.S.";
            case "WF":
                return "🇼🇫 Wallis and Futuna";
            case "EH":
                return "🇪🇭 Western Sahara";
            case "YE":
                return "🇾🇪 Yemen";
            case "ZM":
                return "🇿🇲 Zambia";
            case "ZW":
                return "🇿🇼 Zimbabwe";
            default:
                return "❓ Unknown country";
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