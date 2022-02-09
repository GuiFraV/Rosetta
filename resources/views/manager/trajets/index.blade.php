@extends('manager.navbar')
@section('content')

@if(!empty(Session::get('validationError')))
    <script>toastr.warning("{{ Session::get('validationError') }}");</script>
@elseif(!empty(Session::get('created')))
    <script>toastr.success("{{ Session::get('created') }}");</script>
@endif

<div id="map"></div>

<style>
  table {
    /* Not required only for visualizing */
    border-collapse: collapse;
    width: 100%;
  }

  table thead tr th {
    /* Important */
    background-color: red;
    position: sticky;
    z-index: 100;
    top: 0;
  }

  td {
    /* Not required only for visualizing */
    padding: 1em;
  }
  
  .notification {
    text-decoration: none;
    position: relative;
    display: inline-block;
  }

  .notification .badge {
    position: absolute;
    top: -10px;
    right: 50px;
    padding: 5px 10px;
    border-radius: 50%;
    background: red;
    color: white;
  }
</style>

<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Routes Planning</h1>
  </div>
  <br>

  <div class="col-lg-12 margin-tb">
    <a role="button" class="notification btn btn-primary" data-bs-toggle="collapse" href="#searchFiltersCollapse" role="button" aria-expanded="false" aria-controls="searchFiltersCollapse">
      Filter @if ($srcCount > 0) <div class="badge">{{ $srcCount }}</div> @endif
    </a>          
    @if ($srcCount > 0) <a class="btn btn-warning" href="/manager/trajets">Reset</a> @endif
    <form action="{{ route('manager.trajets.create') }}" method="GET" style="display: inline;">
      @if(getManagerType() == "TM")
        <button class="btn btn-success" type="submit" href="{{ route('manager.trajets.create') }}" name="type" value="load">Add Load</button>
        <button class="btn btn-secondary" type="button" onclick="getRoutes();">Copy the Trucks</button>
      @elseif(getManagerType() == "LM")
        <button class="btn btn-success" type="submit" href="{{ route('manager.trajets.create') }}" name="type" value="truck">Add Truck</button>
        <button class="btn btn-secondary" type="button" onclick="getRoutes();">Copy the Loads</button>
      @endif          
      <button class="btn btn-danger float-end" type="button" onclick="location.reload();">Refresh</button>  
      {{-- <button class="btn btn-primary float-end" type="button" style="margin-right: 4px;" onclick="duplicateAll()">Duplicate All</button> --}}
    </form>  
    <br>

    <div class="collapse" id="searchFiltersCollapse">
      <div class="card card-body">
        <form action="{{ route('manager.trajets.index') }}" method="GET" style="display: inline;">
          <div class="container">
            <div class="row">
              <div class="col">
                <label class="control-label" for="srcDepartureCity">From</label>                    
                <div class="card">
                  <div class="card-body from_card">
                    <div class="form-group">
                      <select id="srcDepartureCountry"  class="selectpicker form-control form-select" name="srcDepartureCountry" data-live-search="true" title="Select Country" >
                        @foreach ($countries as $country)
                          <option value={{$country->code}} >{{$country->fullname}}</option>
                        @endforeach
                      </select>
                    </div>
                    <input id="srcDepartureCity" type="text"  placeholder="Enter a city" name="srcDepartureCity" autocomplete="off" runat="server"/>  
                  </div>
                </div>          
              </div>
              <div class="col">
                <label class="control-label" for="srcArrivalCity">To</label>                    
                <div class="card">
                  <div class="card-body to_card">
                    <div class="form-group">
                      <select id="srcArrivalCountry"  class="selectpicker form-control form-select" name="srcArrivalCountry" data-live-search="true"  title="Select Country" >
                        @foreach ($countries as $country)
                          <option value={{$country->code}} >{{$country->fullname}}</option>
                        @endforeach
                      </select>
                    </div>
                    <input id="srcArrivalCity" type="text"  placeholder="Enter a city" name="srcArrivalCity" autocomplete="off" runat="server"/>  
                  </div>
                </div>          
              </div>
              <div class="col">
                <div class="row" style="padding-bottom: 2%;">
                  <label for="srcManager" class="form-label">Manager</label>
                  <select class="form-select" name="srcManager" id="srcManager" style="display: block; width: 100%; padding: .375rem .75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #212529; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; -webkit-appearance: none;    -moz-appearance: none;    appearance: none;    border-radius: .25rem;    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;">
                    <option value="" selected>Select a manager</option>
                    @foreach (getallmanagers() as $item)
                      <option value="{{$item->id}}">{{$item->first_name}} {{$item->last_name}}</option>
                    @endforeach
                  </select>    
                </div>                
                <div class="row" style="padding-top: 2%;">
                  <label for="srcZone" class="form-label">Zone</label>
                  <select class="form-select" name="srcZone" id="srcZone" style="display: block; width: 100%; padding: .375rem .75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #212529; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; -webkit-appearance: none;    -moz-appearance: none;    appearance: none;    border-radius: .25rem;    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;">
                    <option value="" selected>Select a zone</option>
                    @foreach ($zones as $zone)
                      <option value="{{$zone->id}}">{{$zone->zone_name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm" style="display: flex; align-items: center;">
                  <a class="btn btn-warning my-auto" href="/manager/trajets" style="margin-left: 25%; margin-right: 6%;">Reset</a>                
                  <button class="btn btn-primary my-auto" type="submit" style="margin-right: 25%; margin-left: 6%;">Search</button>                
              </div>
            </div>   
          </div>                      
        </form>
      </div>
    </div>
  </div>
  @if (json_decode($data) == [])
    <div class="container text-center">
      <h1>Oops!</h1>
      <h2>There are no routes with this filter.</h2>
    </div>
  @else
    <table class="table align-middle" >      
      <tr>
        <th style="font-size: 85%"></th>
        <th style="font-size: 85%">Manager</th>
        <th style="font-size: 85%">Date of departure</th>
        <th style="font-size: 85%">From</th>
        <th style="font-size: 85%">To</th>
        <th style="font-size: 85%">Distance</th>
        <th style="font-size: 85%">Type</th>
        <th style="font-size: 85%">Key</th>
        <th style="font-size: 85%">Stars</th>
        <th style="font-size: 85%">Created at</th>
        <th style="font-size: 85%"></th>
        <th style="font-size: 85%"></th>
        <th style="font-size: 85%"></th>
      </tr>      
      @foreach ($zones as $item)
        <tr class="table-light" id="zone{{ $item->id }}">
          <th colspan="11" style="text-align: center">{{ $item->zone_name }}</th>            
          @foreach ($data as $key)
            @if ($key->zone_name == $item->zone_name)
              <tr id="ln{{ $key->id }}" class="{{ isset($key->matched_to) ? 'text-decoration-line-through' : '' }}">
                

                <td>                                         
                  <a href="#" from_l="{{ $key->from_others }}" to_l="{{ $key->to_others }}" typebtn="openmapps"><span style="color: Dodgerblue;" title="Open Maps" class="fa fa-map-marked-alt" ></span></a>
                </td>
                <td style="font-size: 75%">{{ getManagerName($key->manager_id, "") }}</td>
                <td style="font-size: 75%">{{ date('d-m-Y', strtotime($key->date_depart)) }}</td>
                <td style="font-size: 75%">{{ $key->from_others }}</td>
                <td style="font-size: 75%">{{ $key->to_others }}</td>
                <td style="font-size: 75%">{{ ($key->distance != 0) ? (int)(($key->distance)/1000)." Km" : "NaN" }}</td>
                <td style="font-size: 75%">
                  @if($key->vans != 0)
                    {{ $key->vans }} <span class="fa fa-car"  style="align-self: center"></span>
                  @endif
                  @if(strval($key->full_load) == "1")
                    FL
                  @endif
                  @if($key->used_cars == 1)
                    :UC 
                  @endif
                </td>                  
                @if ($key->key == 1)
                  <td><span class="fa fa-key" style="align-self: center"></span></td>
                @else
                  <td style="font-size: 75%"></td>
                @endif                                                          
                <td>
                  @if ($key->stars == 1)
                    <span class="far fa-star" style="align-self: center" title="*"></span>                    
                  @elseif ($key->stars == 2)
                    <span class="fas fa-star-half-alt" style="align-self: center" title="**"></span>                    
                  @elseif ($key->stars == 3)
                    <span class="fas fa-star" style="align-self: center" title="***"></span>
                  @endif
                </td>                  
                <td style="font-size: 75%">{{ date('H:i', strtotime($key->created_at)) }}</td>                  
                <td>
                  @if (isset($key->comment))
                    <a role="button" class="bi bi-chat-square-text text-warning" style="font-size: 1.4rem;" id="buttonComment" onclick="" data-bs-toggle="tooltip" title="" data-bs-original-title="{{ $key->comment }}"></a>
                  @endif
                </td>
                <td>                  
                  @if (!isset($key->matched_to) && $key->manager_id === getManagerId())
                    <a role="button" class="bi bi-arrows-collapse text-success" style="font-size: 1.4rem;" title="Match" onclick="openMatchModal({{ $key->id }}); $('#idInitialElementMatch').val({{ $key->id }}); $('#maxKilometersMatch').val(150); $('#actualRangeVal').html('150Km');"></a>               
                  @endif                  
                </td>                
                <td>
                  @if ($key->visible === 0 && $key->manager_id === getManagerId())                    
                    <a role="button" class="bi bi-node-plus text-primary" style="font-size: 1.4rem;" id="buttonDuplicate" title="Duplicate" onclick="duplicate(this, {{ $key->id }})"></a>
                  @elseif ($key->visible > 0 && $key->manager_id === getManagerId())
                    <a role="button" class="bi bi-node-minus text-danger" style="font-size: 1.4rem;" id="buttonUnduplicate" title="Cancel Duplication" onclick="unduplicate(this, {{ $key->id }})"></a>
                  @endif
                </td>                    
                <td>                  
                  @if ($key->manager_id === getManagerId())
                    <a role="button" class="bi bi-pencil text-warning" style="font-size: 1.4rem;" title="Update" onclick="openModalEdit({{ $key->id }}); $('#updatedId').val({{ $key->id }});"></a>                  
                  @endif
                </td>  
                <td>                  
                  @if ($key->manager_id === getManagerId())
                    <a role="button" class="bi bi-trash text-danger" style="font-size: 1.4rem;" title="Delete" onclick="$('#destroyModal').modal('show'); $('#destroyedId').val({{ $key->id }});"></a>                  
                  @endif
                </td>  
              </tr>
            @endif                
          @endforeach
        </tr>
      @endforeach  
    </table>
  @endif
  <textarea id="routesTextCopy" style="display: none;"></textarea>
</div>

  
<div class="position-sticky float-end text-center" style="bottom: 7%; margin-right: 3.25%;  margin-bottom: -30%;">
  <div class="col">
    <div class="row">
      <a role="button" class="btn btn-outline-primary" style="width: 70%; height: 75%; font-size: 50%;" onclick="$('html,body').animate({scrollTop: $('#zone1').offset().top},'fast');">Z1</a>
    </div>
    <div class="row">
      <a role="button" class="btn btn-outline-primary" style="width: 70%; height: 75%; font-size: 50%;" onclick="$('html,body').animate({scrollTop: $('#zone2').offset().top},'fast');">Z2</a>
    </div>
    <div class="row">
      <a role="button" class="btn btn-outline-primary" style="width: 70%; height: 75%; font-size: 50%;" onclick="$('html,body').animate({scrollTop: $('#zone3').offset().top},'fast');">Z3</a>
    </div>
    <div class="row">
      <a role="button" class="btn btn-outline-primary" style="width: 70%; height: 75%; font-size: 50%;" onclick="$('html,body').animate({scrollTop: $('#zone4').offset().top},'fast');">PL</a>
    </div>
    <div class="row">
      <a role="button" class="btn btn-outline-primary" style="width: 70%; height: 75%; font-size: 50%;" onclick="$('html,body').animate({scrollTop: $('#zone5').offset().top},'fast');">FT</a>
    </div>
    <div class="row">
      <a role="button" class="btn btn-outline-primary" style="width: 70%; height: 75%; font-size: 50%;" onclick="$('html,body').animate({scrollTop: $('#zone6').offset().top},'fast');">PT</a>
    </div>
    <div class="row" style="padding-bottom: 750%;">
      <a role="button" class="btn btn-outline-primary" style="width: 70%; height: 75%; font-size: 50%;" onclick="$('html,body').animate({scrollTop: $('#zone7').offset().top},'fast');">IT</a>
    </div>
    <div class="row">
      <a role="button" class="bi bi-arrow-up-circle text-primary float-end" style="font-size: 1.5rem;" onclick="$('html,body').animate({scrollTop: $('html').offset().top},'fast');"></a>
    </div>
  </div>
</div>

@include('manager.trajets.modals.edit')
@include('manager.trajets.modals.destroy')
@include('manager.trajets.modals.matching')

<script type="text/javascript">      

  /// Tooltip init 
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  /// Search parameters initialization  
  $(function() {
    // Echo the current URL
    // console.log(window.location.href);
    
    // Create a new URLSearchParams from the URL
    var searchParams = new URLSearchParams(window.location.search);
    // Echo each URL parameter
    for(var pair of searchParams.entries()) {
       console.log(pair[0]+ ', '+ pair[1]);
    }

    if(searchParams.get("srcDepartureCountry") != "") {
      $('select[name=srcDepartureCountry]').val(searchParams.get("srcDepartureCountry"));
      $('.selectpicker').selectpicker('refresh');
    }

    if(searchParams.get("srcDepartureCity") != "") {
      $("#srcDepartureCity").val(searchParams.get("srcDepartureCity"));
    }

    if(searchParams.get("srcArrivalCountry") != "") {      
      $('select[name=srcArrivalCountry]').val(searchParams.get("srcArrivalCountry"));
      $('.selectpicker').selectpicker('refresh');
    }

    if(searchParams.get("srcArrivalCity") != "") {
      $("#srcArrivalCity").val(searchParams.get("srcArrivalCity"));
    }

    if(searchParams.get("srcManager") != "") {
      $('#srcManager option[value="'+searchParams.get("srcManager")+'"]').prop('selected', true);
    }

    if(searchParams.get("srcZone") != "") {
      $('#srcZone option[value="'+searchParams.get("srcZone")+'"]').prop('selected', true);
    }
  });

  // Init of the Google Maps API for city autocomplete
  /// On change of country selection
  $('#srcDepartureCountry').on('change',function() {
    var country_code = $(this).find('option:selected').val();    
    srcAutoCityFrom(country_code);
    google.maps.event.addDomListener(window, 'load', srcAutoCityFrom);
  });

  /// CityFrom autocomplete
  function srcAutoCityFrom(country_code) {        
    var input = document.getElementById('srcDepartureCity');
    var id_place = "";
    var from_options = {
      componentRestrictions: { country: country_code },
      strictBounds: true,
      types: ["(cities)"]
    };
    var autocomplete = new google.maps.places.Autocomplete(input, from_options);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
      var place_array = place.name.split(',');
      input.value = place_array[0];
      let city_name = place_array[0] + " ("+country_code+")";
      $("#srcDepartureCity").val(city_name);      
    });
  }

  $('#srcArrivalCountry').on('change',function() {
    var country_code = $(this).find('option:selected').val();    
    srcAutoCityTo(country_code);        
    google.maps.event.addDomListener(window, 'load', srcAutoCityTo);
  });

  function srcAutoCityTo(country_code) {         
    var input = document.getElementById('srcArrivalCity');
    var id_place = "";
    var from_options = {
      componentRestrictions: { country: country_code },
      strictBounds: true,
      types: ["(cities)"]
    };
    var autocomplete = new google.maps.places.Autocomplete(input, from_options);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      var place = autocomplete.getPlace();
      var place_array = place.name.split(',');
      input.value = place_array[0];      
      city_name = place_array[0] + " ("+country_code+")";      
      $("#srcArrivalCity").val(city_name);      
    });
  }



  // Get all routes as text
  function getRoutes() {      
    $.ajax({
      async: true,
      url:"trajets/getRouteList",
      type:"POST",
      cache: false,
      processData: false,
      contentType: false,    
      success:function (data) {        
        let ref = document.getElementById('routesTextCopy'); 
        ref.value = JSON.parse(data); 
        // ref.value = finalStr; 
        ref.style.display='block'; 
        ref.select(); 
        document.execCommand('copy'); 
        ref.style.display = 'none'; 
        ref.value ='';
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });       
  }

  function duplicateAll() {
    $.ajax({
      async: true,
      url:"trajets/duplicateAll",
      type:"POST",
      cache: false,
      processData: false,
      contentType: false,    
      success:function (data) {                        
        console.log(data);
        location.reload();      
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });       
  }

  function unduplicate(e, id) {
    $.ajax({
      async: true,
      url:"trajets/unduplicate/"+id,
      type:"POST",
      data: {"id":id},
      cache: false,
      processData: false,
      contentType: false,    
      success:function(data) {                             
        let ret = JSON.parse(data);
        if(ret.error === 0) {          
          toastr.success("This " + ret.retType + " won't be shown again tomorrow.");      
          e.classList.remove("bi-node-minus");
          e.classList.remove("text-danger");        
          e.classList.add("bi-node-plus");
          e.classList.add("text-primary");
          let tmpOnClick = e.getAttribute('onclick');
          let tempTab = tmpOnClick.split('unduplicate');        
          e.setAttribute('onclick', "duplicate" + tempTab[1]);
        }
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });       
  }

  function duplicate(e, id) {
    $.ajax({
      async: true,
      url:"trajets/duplicate/"+id,
      type:"POST",
      data: {"id":id},
      cache: false,
      processData: false,
      contentType: false,    
      success:function(data) {  
        let ret = JSON.parse(data);                            
        if(ret.error === 0) {
          toastr.success("This " + ret.retType + " will be shown again tomorrow.");      
          e.classList.remove("bi-node-plus");
          e.classList.remove("text-primary");
          e.classList.add("bi-node-minus");
          e.classList.add("text-danger");                
          let tmpOnClick = e.getAttribute('onclick');
          let tempTab = tmpOnClick.split('duplicate');        
          e.setAttribute('onclick', "unduplicate" + tempTab[1]);
        }
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });       
  }

  $('a[typebtn="openmapps"]').click(function() {
    window.open('https://www.google.com/maps/dir/' + $(this).attr("from_l") +'/' + $(this).attr("to_l"));        
  });
</script>

@endsection
