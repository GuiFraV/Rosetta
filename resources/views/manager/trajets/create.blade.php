@extends('manager.navbar')

@section('content')
<div class="mt-5" style="margin-right: 140px;margin-left: 140px;">
  <div class="jumbotron text-center">
    <h1 class="display-6" style="font-family: Segoe UI;">New {{ ucfirst($request->type) }}</h1>
  </div>
  <div class="card-content p-4">
    <form id="formNewRoad" action="{{ route('manager.trajets.store') }}" method="POST" autocomplete="off">
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
      <br>
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
                <input id="from_city_gl" type="text"  placeholder="Enter a city" autocomplete="off" runat="server"/>  
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
                <input id="to_city_gl" type="text"  placeholder="Enter a city" autocomplete="off" runat="server"/>  
              </div>
            </div>
          </div>
        </div>        
        <div class="col-md-2 mt-filter">
          <input class="form-check-input" type="radio" name="key_radios" id="key_id" value="key" checked>
          <label class="form-check-label" for="key_id">Key</label>
          <input class="form-check-input" type="radio" name="key_radios" id="conc_id" value="concurant">
          <label class="form-check-label" for="conc_id">Concurant</label>            
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
      <br>
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
          <label class="form-check-label" for="flexRadioDefault1">Number of vans :</label>
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
      <br>   
      <div class="form-group"><label class="control-label" for="trip_comment">Comment</label> <input type="text" id="trip_comment" name="comment_trajet" class="form-control" /></div>
      <div class="text-center p-3">
        <button type="button" class="btn btn-primary" id="btnSubmitRoad">Create</button>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  
  $(document).ready(function() {       
      $('#flexRadioDefault1').click(function() {
        $('#collapseOne').show();
      });

      $('#flexRadioDefault2').click(function() {
        $('#collapseOne').hide();
      });

      $('#from_cities_button_empty').click(function() {        
        let tmp = $('#trip_additionalCityFrom').val();
        let tmpArr = tmp.split("+");                      
        tmpArr.pop();              
        let final = "";              
        tmpArr.forEach(elem => {
          final += elem + "+";
        });        
        final = final.slice(0, -1);        
        $('#trip_additionalCityFrom').val(final);        
      });

      $('#to_cities_button_empty').click(function() {        
        let tmp = $('#trip_additionalCityTo').val();
        let tmpArr = tmp.split("+");                      
        tmpArr.pop();              
        let final = "";              
        tmpArr.forEach(elem => {
          final += elem + "+";
        });        
        final = final.slice(0, -1);        
        $('#trip_additionalCityTo').val(final);        
      });

      $('#zone_select').on('change',function() {
        var zone_id = $(this).find('option:selected').val();
        
        if(zone_id == 1) {
          $('#from_country_select').val('default');
          $('#from_country_select').html('');
          $('#collapseOne').slideDown();
          $('#btnradio12').replaceWith($('#btnradio12').clone().attr('type', 'checkbox'));
          $("#btnradio12").attr("disabled", true);
          $("#btnradio12").attr("checked", true);
          $('.fullload').show();
        } else if(zone_id == 2) {
          $('#collapseOne').slideDown();
          $('#btnradio12').replaceWith($('#btnradio12').clone().attr('type', 'checkbox'));
          $("#btnradio12").attr("disabled", true);
          $("#btnradio12").attr("checked", true);
          $('.fullload').show();
        } else if(zone_id == 3) {
          $('#collapseOne').slideDown();
          $('#btnradio12').replaceWith($('#btnradio12').clone().attr('type', 'checkbox'));
          $("#btnradio12").attr("disabled", true);
          $("#btnradio12").attr("checked", true);
          $('.fullload').show();
        } else if(zone_id == 4) {
          $('#collapseOne').slideDown();
          $('#btnradio12').replaceWith($('#btnradio12').clone().attr('type', 'radio'));
          $("#btnradio12").attr("disabled", false);
          $("#btnradio12").attr("checked", false);
          $('.fullload').hide();
        } else if(zone_id == 5) {
          $('#collapseOne').slideUp();
          $('.fullload').show();
        } else if(zone_id == 6) {
          $('#collapseOne').slideDown();
          $('.fullload').hide();
        } else if(zone_id == 7) {
          $('#collapseOne').slideDown();
          $('#btnradio12').replaceWith($('#btnradio12').clone().attr('type', 'radio'));
          $('.fullload').show();
        }
      });
      
      $('#from_country_select').on('change',function() {
        var country_code = $(this).find('option:selected').val();
        console.log(country_code);        
        fromcity(country_code);
        google.maps.event.addDomListener(window, 'load', fromcity);           
      });

      function fromcity(country_code) {        
        var input = document.getElementById('from_city_gl');
        var id_place = "";
        var from_options = {
          componentRestrictions: { country: country_code },
          strictBounds: true,
          types: ["(cities)"]
        };
        var autocomplete = new google.maps.places.Autocomplete(input,from_options);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
          var place = autocomplete.getPlace();
          var place_array = place.name.split(',');
          input.value = place_array[0];
          var from_cities = $("#trip_additionalCityFrom").val();
          city_name = place_array[0] + " ("+country_code+")";
          if (from_cities == "") {
            city_name = from_cities + city_name;
          } else {
            city_name = from_cities + " + " + city_name;
          }
          $("#trip_additionalCityFrom").val(city_name);
          $("#from_city_gl").val("");                        
        });
      }

      $('#to_country_select').on('change',function() {
        var country_code = $(this).find('option:selected').val();
        console.log(country_code);        
        tocity(country_code);        
        google.maps.event.addDomListener(window, 'load', fromcity);        
      });

      function tocity(country_code) {        
        var input = document.getElementById('to_city_gl');
        var id_place = "";
        var from_options = {
          componentRestrictions: { country: country_code },
          strictBounds: true,
          types: ["(cities)"]
        };
        var autocomplete = new google.maps.places.Autocomplete(input,from_options);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
          var place = autocomplete.getPlace();
          var place_array = place.name.split(',');
          input.value = place_array[0];
          var from_cities = $("#trip_additionalCityTo").val();
          city_name = place_array[0] + " ("+country_code+")";
          if (from_cities == "") {
            city_name = from_cities + city_name;
          } else {
            city_name = from_cities + " + " + city_name;
          }
          $("#trip_additionalCityTo").val(city_name);
          $("#to_city_gl").val("");                        
        });
      }
  });


  $("#btnSubmitRoad").click(function() {

    let date_depart = $("[name='date_depart']").val();
    let zone_select = $("#zone_select").val();
    let key = $("input[name='key_radios']:checked").val();    
    let stars = $('#trip_cityTo').val();
    let from_cities = $('#trip_additionalCityFrom').val();
    let to_cities = $('#trip_additionalCityTo').val();    
    let vanNumber = $("input[name='btnradio']:checked").val();         
    let comment_trajet = $('#comment_trajet').val();
    
    let boolCheck = false;

    if(date_depart === "") {
      toastr.warning("Form error! Please select a departure date.");
      boolCheck = true;
    }

    if(date_depart < moment().format("Y-MM-D")) {
      toastr.warning("Form error! The departure date can't be before today's date.");
      boolCheck = true;
    }

    if(zone_select === null) {
      toastr.warning("Form error! Please select a zone.");
      boolCheck = true;
    }

    if(zone_select < 1 || zone_select > 7) {
      toastr.warning("Form error! The selected zone is incorrect, please try to reload the page.");
      boolCheck = true;
    }

    if(key != "key" && key != "concurant") {
      toastr.warning("Form error! Please select a key.");
      boolCheck = true;
    }

    if(stars === null || (stars != 1 && stars != 2 && stars != 3)) {
      toastr.warning("Form error! Please select a number of stars.");
      boolCheck = true;
    }

    if(from_cities === "") {
      toastr.warning("Form error! Please select a loading city.");
      boolCheck = true;
    }

    if(from_cities.length > 191) {
      toastr.warning("Form error! The loading city field can't exceed 190 characters.");
      boolCheck = true;
    }
    
    if(to_cities === "") {
      toastr.warning("Form error! Please select an unloading city.");
      boolCheck = true;
    }

    if(to_cities.length > 191) {
      toastr.warning("Form error! The unloading city field can't exceed 190 characters.");
      boolCheck = true;
    }

    if((vanNumber < 1 || vanNumber > 11) || vanNumber === "on") {
      toastr.warning("Form error! The number of van is incorrect.");
      boolCheck = true;
    }

    if(boolCheck)
      return 1;
    
    $("#formNewRoad").submit();
  });

    

</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsEtLFAR_7CkTeUiXCYUK-lgz44Ix2Xjs&libraries=places&language=en"></script>

@endsection