@extends('manager.navbar')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- Compiled and minified MultiSelect JS -->
    
<!-- Compiled and minified MultiSelect CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" integrity="sha512-mR/b5Y7FRsKqrYZou7uysnOdCIJib/7r5QeJMFvLNHNhtye3xJp1TdJVPLtetkukFn227nKpXD9OjUc09lx97Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />   
<div class="mt-5" style="margin-right: 140px;margin-left: 140px;">
    <div class="card-header text-center">
        <h2>Creation form</h2>
    </div>
    <div class="card-content p-4">
        <form action="{{ route('manager.trajets.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label required">Date of departure</label>
                        <br>
                        <input type="date" name="date_depart" style="display: block;    width: 100%;    padding: .375rem .75rem;    font-size: 1rem;    font-weight: 400;    line-height: 1.5;    color: #212529;    background-color: #fff;    background-clip: padding-box;    border: 1px solid #ced4da;    -webkit-appearance: none;    -moz-appearance: none;    appearance: none;    border-radius: .25rem;    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;" value="<?php echo date("Y-m-d");?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label required" for="trip_zone">Zone</label>
                        {{-- <select id="trip_zone" name="zone_select" class="form-control">
                            @foreach ($zones as $zone)
                                <option value={{$zone->id}}>{{$zone->zone_name}}</option>
                            @endforeach
                            
                        </select> --}}
                        <select id="zone_select" name="zone_select" class="form-control">
                            @if ($request->type == "load")
                                @foreach ($zones as $zone)
                                    @if ($zone->id == 1 || $zone->id == 2 || $zone->id == 3 || $zone->id == 4 )
                                        <option value={{$zone->id}}>{{$zone->zone_name}}</option>
                                    @endif
                                @endforeach
                            @else
                                @foreach ($zones as $zone)
                                    @if ($zone->id == 5 || $zone->id == 6 || $zone->id == 7 )
                                        <option value={{$zone->id}}>{{$zone->zone_name}}</option>
                                    @endif
                                @endforeach
                            @endif
                            
                        </select>
                          
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="col">
                        <label class="control-label required" for="trip_cityFrom">From</label>
                    </div>
                    <div class="col" id="from_div">
                        <div class="col-md-8 card">
                            <div class="card-body from_card">
                                <div class="form-group">
                                    <select id="from_country_select"  class="selectpicker form-control SelectWine" name="from_country_select1" data-live-search="true"  title="Select Country" >
                                        @foreach ($countries as $country)
                                            <option value={{$country->code}} >{{$country->fullname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input id="from_country_hidden" name="from_country_hidden" type="hidden" value="">
                                {{-- <div class="form-group">
                                    <select id="from_city_select" class="form-control" name="from_city" data-live-search="true">
                                        
                                    </select>
                                </div> --}}
                                <input id="from_city_gl" type="text"  placeholder="Enter a city" autocomplete="off" runat="server" />  

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="col">
                        <label class="control-label required" for="trip_cityFrom">To</label>
                    </div>
                    <div class="col" id="to_div">
                        <div class="col-md-8 card">
                            <div class="card-body to_card">
                                <div class="form-group">
                                    <select id="to_country_select"  class="selectpicker form-control SelectWine" name="to_country_select1" data-live-search="true"  title="Select Country" >
                                        @foreach ($countries as $country)
                                            <option value={{$country->code}} >{{$country->fullname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input id="to_country_hidden" name="to_country_hidden" type="hidden" value="">
                                {{-- <div class="form-group">
                                    <select id="from_city_select" class="form-control" name="from_city" data-live-search="true">
                                        
                                    </select>
                                </div> --}}
                                <input id="to_city_gl" type="text"  placeholder="Enter a city" autocomplete="off" runat="server" />  

                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col">
                    <label class="control-label required" for="trip_cityTo">To</label>
                    <div class="col">
                        
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="selectpicker form-control" name="from_country" data-live-search="true"  title="Select Country" >
                                    @foreach ($countries as $country)
                                        <option value={{$country->id}}>{{$country->country_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control from_city_select" name="from_city" data-live-search="true">
                                    <option value="1">1</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="selectpicker form-control" name="to_country" data-live-search="true"  title="Select Country" >
                                    @foreach ($countries as $country)
                                        <option value={{$country->id}}>{{$country->country_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="to_city" data-live-search="true" multiple>
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                </div> --}}
                
                <div class="col-md-2 mt-filter">
                    <input class="form-check-input" type="radio" name="key_radios" id="key_id" value="key" checked>
                    <label class="form-check-label" for="key_id">
                        Key
                    </label>
                    <input class="form-check-input" type="radio" name="key_radios" id="conc_id" value="concurant">
                    <label class="form-check-label" for="conc_id">
                        Concurant
                    </label>
                    
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label required">Star(s)</label>
                        <select id="trip_cityTo" name="stars_select" class="form-control">
                            <option value="1">*</option>
                            <option value="2" selected>**</option>
                            <option value="3">***</option>
                            
                        </select>
                    </div>
                    
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="trip_additionalCityFrom">(From) cities separated by +</label> 
                
                <div class="input-group mb-3">
                    <input type="text" id="trip_additionalCityFrom" name="from_cities" class="form-control" readonly />
                    <button class="btn btn-outline-secondary" type="button" id="from_cities_button_empty">X</button>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label" for="trip_additionalCityTo">(To) cities separated by +</label> 
                <div class="input-group mb-3">
                    <input type="text" id="trip_additionalCityTo" name="to_cities" class="form-control" readonly />
                    <button class="btn btn-outline-secondary" type="button" id="to_cities_button_empty">X</button>
                </div>
            </div>
            <div class="form-group">
                <div id="collapseOne">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Number of vans :
                    </label>
                    <br>
                    <div class="btn-group" role="group" >
    
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" value="1"  >
                        <label class="btn btn-outline-primary" for="btnradio1">1</label>
                      
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" value="2">
                        <label class="btn btn-outline-primary" for="btnradio2">2</label>
                      
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off" value="3">
                        <label class="btn btn-outline-primary" for="btnradio3">3</label>
    
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off" value="4">
                        <label class="btn btn-outline-primary" for="btnradio4">4</label>
    
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio5" autocomplete="off" value="5">
                        <label class="btn btn-outline-primary" for="btnradio5">5</label>
    
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio6" autocomplete="off" value="6">
                        <label class="btn btn-outline-primary" for="btnradio6">6</label>
    
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio7" autocomplete="off" value="7">
                        <label class="btn btn-outline-primary" for="btnradio7">7</label>
    
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio8" autocomplete="off" value="8">
                        <label class="btn btn-outline-primary" for="btnradio8">8</label>
    
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio9" autocomplete="off" value="9">
                        <label class="btn btn-outline-primary" for="btnradio9">9</label>
    
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio10" autocomplete="off" value="10">
                        <label class="btn btn-outline-primary" for="btnradio10">10</label>
    
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio11" autocomplete="off" value="11">
                        <label class="btn btn-outline-primary" for="btnradio11">11</label>
                        &nbsp &nbsp
                        <div class="fullload">
                            <input type="checkbox" class="btn-check" name="btnradio" id="btnradio12" autocomplete="off" checked disabled>
                            <label class="btn btn-outline-primary" for="btnradio12">Full Load</label>
                        </div>
                        &nbsp &nbsp
                        <div class="usedcars">
                            <input class="form-check-input" type="checkbox"  name="usedcars" id="usedcars" value="checked">
                            <label class="form-check-label" for="usedcars">Used Cars</label>
                        </div>
                        
                    </div>
                </div>
                
                
                
                
            </div>
            
            <div class="form-group"><label class="control-label" for="trip_comment">Comment</label> <input type="text" id="trip_comment" name="comment_trajet" class="form-control" /></div>
            <div class="text-center p-3">
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    
    

    $(document).ready(function(){
        
        $('#flexRadioDefault1').click(function(){
            $('#collapseOne').show(); //to show
        });
        $('#flexRadioDefault2').click(function(){
            $('#collapseOne').hide();  //to hide
        });
        $('#from_cities_button_empty').click(function(){
            $('#trip_additionalCityFrom').val('');  //to hide
        });
        $('#to_cities_button_empty').click(function(){
            $('#trip_additionalCityTo').val('');  //to hide
        });

        $('#zone_select').on('change',function() {

            var zone_id = $(this).find('option:selected').val();
            if (zone_id == 1  ){
                $('#from_country_select').val('default');
                $('#from_country_select').html('');
                $('#collapseOne').slideDown();
                $('#btnradio12').replaceWith($('#btnradio12').clone().attr('type', 'checkbox'));
                $("#btnradio12").attr("disabled", true);
                $("#btnradio12").attr("checked", true);
                $('.fullload').show();
            }else if (zone_id == 2 ){
                $('#collapseOne').slideDown();
                $('#btnradio12').replaceWith($('#btnradio12').clone().attr('type', 'checkbox'));
                $("#btnradio12").attr("disabled", true);
                $("#btnradio12").attr("checked", true);
                $('.fullload').show();
            }else if (zone_id == 3 ){
                $('#collapseOne').slideDown();
                $('#btnradio12').replaceWith($('#btnradio12').clone().attr('type', 'checkbox'));
                $("#btnradio12").attr("disabled", true);
                $("#btnradio12").attr("checked", true);
                $('.fullload').show();
            }else if (zone_id == 4){
                $('#collapseOne').slideDown();
                $('#btnradio12').replaceWith($('#btnradio12').clone().attr('type', 'radio'));
                $("#btnradio12").attr("disabled", false);
                $("#btnradio12").attr("checked", false);
                $('.fullload').hide();
            }else if (zone_id == 5){
                $('#collapseOne').slideUp();
                $('.fullload').show();
            }else if (zone_id == 6){
                $('#collapseOne').slideDown();
                $('.fullload').hide();

            }else if (zone_id == 7){
                $('#collapseOne').slideDown();
                $('#btnradio12').replaceWith($('#btnradio12').clone().attr('type', 'radio'));
                $('.fullload').show();

            }
            
            
        });
        
        $('#from_country_select').on('change',function() {
            var country_code = $(this).find('option:selected').val();
            console.log(country_code);
            console.log("1");
            fromcity(country_code);
            console.log("4");
            google.maps.event.addDomListener(window, 'load', fromcity);
            console.log("5");
            
            // var query = $(this).val();
            // $("#zone_select").find('option').attr("selected",false) ;
            // var zone1 = ["3", "6", "8", "12", "4", "13", "17", "16", "21", "23", "28", "25", "24"];
            // if($.inArray(query, zone1) != -1){
            //     $('#zone_select option[value="1"]').attr("selected", "selected");
            // }
            // var zone2 = ["1", "2", "11", "7", "9", "29", "15", "18", "20", "30", "27"];
            // if($.inArray(query, zone2) != -1){
            //     $('#zone_select option[value="2"]').attr("selected", "selected");
            // }
            // var zone3 = ["31", "26", "10", "32", "22"];
            // if($.inArray(query, zone3) != -1){
            //     $('#zone_select option[value="3"]').attr("selected", "selected");
            // }
            
            // $.ajax({
            //     url:"/manager/searchcity",
            //     type:"GET",
            //     data:{'country_id':query},
            //     success:function (data) {
            //         $('#from_city_select').val('default');
            //         $('#from_city_select').html('');
                    

            //         var texthtm = "";
            //         $.each(data, function (i, elem) {
            //             texthtm =texthtm + "<option value="+elem.country_code+">"+elem.city_name+"</option>";
            //         });
            //         $("#from_city_select").append(texthtm);
            //         $('#from_city_select').selectpicker("refresh");
            //     }
            // });
           
        });
        function fromcity(country_code){
            console.log("2");
            var input = document.getElementById('from_city_gl');
            var id_place = "";
            var from_options = {
                componentRestrictions: { country: country_code },
                strictBounds: true,
                types: ["(cities)"],
            };
            var autocomplete = new google.maps.places.Autocomplete(input,from_options);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                var place_array = place.name.split(',');
                input.value = place_array[0];
                var from_cities = $("#trip_additionalCityFrom").val()
                city_name = place_array[0] + " ("+country_code+")";
                if (from_cities == ""){
                    city_name = from_cities + city_name 
                }else{
                    city_name = from_cities + " + " + city_name 
                }
                $("#trip_additionalCityFrom").val(city_name);
                $("#from_city_gl").val("");
                
                
            });
        }
        $('#to_country_select').on('change',function() {
            var country_code = $(this).find('option:selected').val();
            console.log(country_code);
            console.log("1");
            tocity(country_code);
            console.log("4");
            google.maps.event.addDomListener(window, 'load', fromcity);
            console.log("5");
           
        });
        function tocity(country_code){
            console.log("2");
            var input = document.getElementById('to_city_gl');
            var id_place = "";
            var from_options = {
                componentRestrictions: { country: country_code },
                strictBounds: true,
                types: ["(cities)"],
            };
            var autocomplete = new google.maps.places.Autocomplete(input,from_options);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                var place_array = place.name.split(',');
                input.value = place_array[0];
                var from_cities = $("#trip_additionalCityTo").val()
                city_name = place_array[0] + " ("+country_code+")";
                if (from_cities == ""){
                    city_name = from_cities + city_name 
                }else{
                    city_name = from_cities + " + " + city_name 
                }
                $("#trip_additionalCityTo").val(city_name);
                $("#to_city_gl").val("");
                
                
            });
        }
        
        
        
    });
    
    
   
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsEtLFAR_7CkTeUiXCYUK-lgz44Ix2Xjs&libraries=places&language=en"></script>


@endsection
