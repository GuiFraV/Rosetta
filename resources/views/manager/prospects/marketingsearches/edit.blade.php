@extends('manager.navbar')

@section('content')
    @if (getManagerId() != $marketingSearch->creator) 
        {{ app()->call('App\Http\Controllers\MarketingSearchController@index'); }}
    @endif
    <div class="container col-6">
        <h2>Edit an existing marketing search</h2><br>
        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <p>Whooooops! Something went wrong.</p><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('manager.marketingsearch.update', $marketingSearch->id) }}" method="post">
            @csrf
            @method('put')
            <div class="row">
                <div class="col">
                    <label for="name" class="form-label">Company name</label>
                    <input type="text" class="form-control" name="name" value="{{ $marketingSearch->name }}" required>
                </div>
                <div class="col">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="countryAuto" value="{{ countryCodeToEmojiName($marketingSearch->country) }}">
                    <input type="hidden" name="country" value="{{ $marketingSearch->country }}">
                </div>
            </div><br>
            <div class="row">
                <div class="col">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{$marketingSearch->email}}">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">Phone number</label>
                    <div class="input-group">
                        <span class="input-group-text" id="callingCode"></span>
                        <input type="hidden" name="callingCodeForm">
                        <input type="text" class="form-control" aria-label="Phone number" aria-describedby="Phone number" name="phone" value="{{ $marketingSearch->phone }}">
                    </div>
                </div>
            </div><br>
            <div class="row">
                <div class="col">
                    <label for="type" class="form-label">Type :&nbsp;&nbsp;&nbsp;</label>
                    @if ($marketingSearch->type === "Client")
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" value="Client" checked>
                            <label class="form-check-label" for="type">Client</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" value="Carrier">
                            <label class="form-check-label" for="type">Carrier</label>
                        </div>
                    @elseif ($marketingSearch->type === "Carrier")
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" value="Client">
                            <label class="form-check-label" for="type">Client</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" value="Carrier" checked>
                            <label class="form-check-label" for="type">Carrier</label>
                        </div>
                    @endif
                </div>
            </div><br>
            <div class="float-end">
                <a href="../" class="btn btn-danger">Return</a>
                <button type="submit" class="btn btn-primary">Update marketing search</button>
            </div>
        </form>
    </div>

    <script>
        
        // Manipulate the country code value onload to set the calling code
        $(function() {
            if($('[name="country"]').val() != "") {
                let code = '+'+countryCodeToCallingCode($('[name="country"]').val());
                $('#callingCode').html(code);    
                $('[name="callingCodeForm"]').val(code);
                let tmpVal = $('[name="phone"]').val();
                $('[name="phone"]').val(tmpVal.substr(code.length));
            }
        });

        $(document).ready(function() {
            // Static definition of the autocomplete values, to change for a dynamic one, linked to the database
            var autoCountriesArr = [
                {label:"🇦🇽 Åland Islands", value:"AX"},
                {label:"🇦🇱 Albania", value:"AL"},
                {label:"🇦🇩 Andorra", value:"AD"},
                {label:"🇦🇹 Austria", value:"AT"},
                {label:"🇧🇾 Belarus", value:"BY"},
                {label:"🇧🇪 Belgium", value:"BE"},
                {label:"🇧🇦 Bosnia and Herzegovina", value:"BA"},
                {label:"🇧🇬 Bulgaria", value:"BG"},
                {label:"🇭🇷 Croatia", value:"HR"},
                {label:"🇨🇾 Cyprus", value:"CY"},
                {label:"🇨🇿 Czech Republic", value:"CZ"},
                {label:"🇩🇰 Denmark", value:"DK"},
                {label:"🇪🇪 Estonia", value:"EE"},
                {label:"🇫🇴 Faroe Islands", value:"FO"},
                {label:"🇫🇮 Finland", value:"FI"},
                {label:"🇫🇷 France", value:"FR"},
                {label:"🇩🇪 Germany", value:"DE"},
                {label:"🇬🇮 Gibraltar", value:"GI"},
                {label:"🇬🇷 Greece", value:"GR"},
                {label:"🇬🇬 Guernsey", value:"GG"},
                {label:"🇻🇦 Holy See (Vatican City State)", value:"VA"},
                {label:"🇭🇺 Hungary", value:"HU"},
                {label:"🇮🇸 Iceland", value:"IS"},
                {label:"🇮🇪 Ireland", value:"IE"},
                {label:"🇮🇲 Isle of Man", value:"IM"},
                {label:"🇮🇹 Italy", value:"IT"},
                {label:"🇯🇪 Jersey", value:"JE"},
                {label:"🇱🇻 Latvia", value:"LV"},
                {label:"🇱🇮 Liechtenstein", value:"LI"},
                {label:"🇱🇹 Lithuania", value:"LT"},
                {label:"🇱🇺 Luxembourg", value:"LU"},
                {label:"🇲🇰 Macedonia, the former Yugoslav Republic of", value:"MK"},
                {label:"🇲🇹 Malta", value:"MT"},
                {label:"🇲🇩 Moldova, Republic of", value:"MD"},
                {label:"🇲🇨 Monaco", value:"MC"},
                {label:"🇲🇪 Montenegro", value:"ME"},
                {label:"🇳🇱 Netherlands", value:"NL"},
                {label:"🇳🇴 Norway", value:"NO"},
                {label:"🇵🇱 Poland", value:"PL"},
                {label:"🇵🇹 Portugal", value:"PT"},
                {label:"🇷🇴 Romania", value:"RO"},
                {label:"🇷🇺 Russian Federation", value:"RU"},
                {label:"🇷🇸 Serbia", value:"RS"},
                {label:"🇸🇰 Slovakia", value:"SK"},
                {label:"🇸🇮 Slovenia", value:"SI"},
                {label:"🇪🇸 Spain", value:"ES"},
                {label:"🇸🇯 Svalbard and Jan Mayen", value:"SJ"},
                {label:"🇸🇪 Sweden", value:"SE"},
                {label:"🇨🇭 Switzerland", value:"CH"},
                {label:"🇺🇦 Ukraine", value:"UA"},
                {label:"🇬🇧 United Kingdom", value:"GB"}
            ];

            // Autocomplete of the country input
            $('#countryAuto').autocomplete({        
                source: autoCountriesArr,
                select: function( event, ui ) {
                    // Set the value of the label input and the hidden input value
                    $("#countryAuto").val(ui.item.label);
                    $('[name="country"]').val(ui.item.value);
                    // Use the newly acquired value to search for the calling code
                    let code = '+'+countryCodeToCallingCode(ui.item.value);
                    $('#callingCode').html(code);
                    $('[name="callingCodeForm"]').val(code);
                    return false;
                }
            });

            /*
            $("#countryAuto").on('change', function() {
                //let code = '+'+countryCodeToCallingCode(this.value);
                if($("#countryAuto").val() == "") {
                    $('#callingCode').html('');
                    $('[name="country"]').val('');
                    $("#countryAuto").val('')
                    //$('[name="callingCodeForm"]').val('');
                }
            });
            */

        });

        function countryCodeToCallingCode(countryCode) {
            switch (countryCode) {
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
    </script>

@endsection