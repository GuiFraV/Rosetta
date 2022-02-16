@php
    $zones = App\Models\Zone::get();
    $countries  = App\Models\Country::where("isActive", 1)->orderBy('fullname')->get();
@endphp

<style>
  .pac-container {
      z-index: 10000 !important;
  }
</style>

<div class="modal fade" id="editRouteModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="editRouteModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="editRouteModalLabel">Edit a route</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form id="formEditRoute" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="editedId" id="editedId" hidden/>
        
        <div class="modal-body" id="modal_content">                      
          <div class="container-fluid">
            <div class="row">
              <div class="col">
                <label class="control-label" for="from_city_gl">From</label>                          
                <div class="card">
                  <div class="card-body from_card">
                    <div class="form-group">
                      <select id="from_country_select"  class="selectpicker form-control SelectWine" name="from_country_select1" data-live-search="true" title="Select Country" >
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
              <div class="col">
                <label class="control-label required" for="trip_cityFrom">To</label>                
                <div class="card">
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
              <div class="col">          
                <label class="control-label required">Date of departure</label>            
                <input type="date" name="date_depart" id="date_depart" min="{{ date('Y-m-d') }}" style="display: block; width: 100%; padding: .375rem .75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #212529; background-color: #fff;    background-clip: padding-box;    border: 1px solid #ced4da;    -webkit-appearance: none;    -moz-appearance: none;    appearance: none;    border-radius: .25rem;    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;" value="{{ date('Y-m-d') }}">                        
                <div class="row">
                  <div class="col" style="margin-top: 10px;">
                    <input class="form-check-input" type="radio" name="key_radios" id="key_id" value="key" checked>
                    <label class="form-check-label" for="key_id">Key</label>
                    <br>
                    <input class="form-check-input" type="radio" name="key_radios" id="conc_id" value="concurant">
                    <label class="form-check-label" for="conc_id">Concurant</label>            
                  </div>
                  <div class="col">              
                    <label class="control-label required">Star(s)</label>
                    <select id="trip_cityTo" name="stars_select" class="form-control">
                      <option value="1">*</option>
                      <option value="2" selected>**</option>
                      <option value="3">***</option>                
                    </select>                   
                  </div>
                </div>                      
              </div>
            </div>      
            <br>
            <div class="form-group">
              <label class="control-label" for="trip_additionalCityFrom">From (cities separated by +)</label>         
              <div class="input-group mb-3">
                <input type="text" id="trip_additionalCityFrom" name="from_cities" class="form-control" readonly />
                <button class="btn btn-outline-secondary" type="button" title="Clear the last added city" id="btnClearFrom">X</button>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label" for="trip_additionalCityTo">To (cities separated by +)</label> 
              <div class="input-group mb-3">
                <input type="text" id="trip_additionalCityTo" name="to_cities" class="form-control" readonly />
                <button class="btn btn-outline-secondary" type="button" title="Clear the last added city" id="btnClearTo">X</button>
              </div>
            </div>
            <div class="form-group">
              <div id="collapseOne">
                <label class="form-check-label" for="flexRadioDefault1">Number of vehicles :</label>
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
                    <input type="checkbox" class="btn-check" name="btnradio" id="btnradio12" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio12">Full Load</label>
                  </div>                                        
                </div>
              </div>
            </div>
            <br>
            <div class="btn-group" role="group" >
              <div class="usedcars my-auto">
                <input class="form-check-input" type="checkbox"  name="usedcars" id="usedcars" value="checked">
                <label class="form-check-label" for="usedcars">Used Vehicles</label>
              </div>     
              @if(getManagerType() == "LM")         
                &nbsp &nbsp
                <div class="my-auto">
                  <input class="form-check-input" type="checkbox"  name="intergateTruck" id="intergateTruck" value="1">
                  <label class="form-check-label" for="usedcars">Intergate Truck</label>
                </div>
              @endif
              &nbsp &nbsp
              <div class="my-auto">
                <input class="form-check-input" type="checkbox"  name="urgentRoute" id="urgentRoute" value="1">
                <label class="form-check-label" for="urgentRoute">Urgent</label>
              </div>
            </div>              
            <br>   
            <br>   
            <div class="form-group">
              <label class="control-label" for="trip_comment">Comment</label> 
              <input type="text" id="trip_comment" name="comment_trajet" class="form-control"/>
            </div>           
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <input type="button" class="btn btn-primary" id="btnSendEditRoute" value="Edit Route"/>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  
  function wipeDataOnModal() {
    $('#editedId').val('');
    $('#date_depart').val('');
    $('#trip_additionalCityFrom').val('');
    $('#trip_additionalCityTo').val('');
    $('#trip_cityTo').removeAttr("selected");
    $('#from_country_select').val('');    
    $('#from_country_select').val('');
    $("input[name=key_radios]").removeAttr("selected");
    $("#btnradio1, #btnradio2, #btnradio3, #btnradio4, #btnradio5, #btnradio6, #btnradio7, #btnradio8, #btnradio9, #btnradio10, #btnradio11, #btnradio12").prop("checked", false);
    $("#usedcars").prop("checked", false);     
    $("#intergateTruck").prop("checked", false);
    $("#urgentRoute").prop("checked", false);
    $("#trip_comment").val('');
  }

  $(function() {
    wipeDataOnModal();
  });

  $('#editRouteModal').on('hidden.bs.modal', function () {
    wipeDataOnModal();
  });
  
  function openModalEdit(id) {    
    $.ajax({
      async: true,
      type: "POST",
      url: "trajets/edit/"+id,
      dataType: "JSON",
      data: {"id": id},
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {        
        if(data.error) {
          toastr.warning(data.message);          
          return 1;
        } else {    
          $('#editedId').val(id);
          $('#date_depart').val(data.trajet.date_depart);
          $('#trip_additionalCityFrom').val(data.trajet.from_others);
          $('#trip_additionalCityTo').val(data.trajet.to_others);
          $('#trip_cityTo option[value="'+data.trajet.stars+'"]').prop('selected', true);
          if(data.trajet.key === 0) {            
            $("input[name=key_radios][value='concurant']").prop("checked", true);          
          } else if (data.trajet.key === 1) {            
            $("input[name=key_radios][value='key']").prop("checked", true);
          }
          if(data.trajet.full_load) {
            $("#btnradio12").prop("checked",true);
          } else {
            $("#btnradio"+data.trajet.vans).prop("checked", true);
          }
          if(data.trajet.used_cars)
            $("#usedcars").prop("checked", true ); 
          if(data.trajet.intergate_truck)
            $("#intergateTruck").prop("checked", true);
          if(data.trajet.urgent)
            $("#urgentRoute").prop("checked", true ); 
          $("#trip_comment").val(data.trajet.comment);
          $('#editRouteModal').modal('show');
          return 0;          
        }
      },
      error: function (request, status, error) {
        console.log(error);
        return 1;
      }
    });
  }
   
  /// Form validation then sending via Ajax
  $("#btnSendEditRoute").click(function() {
    let tmpDepart = $("[name='date_depart']").val();
    let date_depart = moment(tmpDepart).format('YYYY-MM-DD');
    let key = $("input[name='key_radios']:checked").val();    
    let stars = $('#trip_cityTo').val();
    let from_cities = $('#trip_additionalCityFrom').val();
    let to_cities = $('#trip_additionalCityTo').val();    
    let vanNumber = $("input[name='btnradio']:checked").val(); 
    let intergateTruck = $('#intergateTruck').prop('checked') ? 1 : 0;
    let used_cars = $('#usedcars').prop('checked') ? 1 : 0;
    let urgent = $('#urgentRoute').prop('checked') ? 1 : 0;
    let comment_trajet = $('#trip_comment').val();
    
    let boolCheck = false;
    
    if(tmpDepart === "") {
      toastr.warning("Form error! Please select a departure date.");
      boolCheck = true;
    }
	
	  let tmpToday = moment().format('YYYY-MM-DD');;
	  if(date_depart < tmpToday) {
	  	toastr.warning("Form error! The departure date can't be before today's date.");
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

    if((vanNumber < 1 || vanNumber > 11) || vanNumber === undefined) {
      toastr.warning("Form error! The number of vehicles is incorrect.");
      boolCheck = true;
    }

    if(boolCheck)
      return 1;
    
    let fd = new FormData();
    fd.append("date_depart", date_depart);
    fd.append("key", key);
    fd.append("stars", stars);
    fd.append("from_cities", from_cities);
    fd.append("to_cities", to_cities);
    fd.append("vanNumber", vanNumber);
    fd.append("intergateTruck", intergateTruck);
    fd.append("used_cars", used_cars);
    fd.append("urgent", urgent);
    fd.append("comment_trajet", comment_trajet);    
    
    toastr.info("This operation can take time, please be patient.");

    $.ajax({
      async: true,
      type: "POST",
      url: "trajets/update/"+$('#editedId').val(),
      data: fd,
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        console.log(data);
        let reponse = JSON.parse(data);        
        if(reponse.error) {
          // display error
          console.log(reponse['error']);
          //$('#partnerDataTable').DataTable().ajax.reload();
          toastr.warning("The specified partner has not been found. Please try to reload the page.");
          toastr.warning("There has been an error during the edition of the partner. Please retry to send your edition.");
        } else if(!reponse.error) {
          $('#ln'+reponse.id).remove();
          $(reponse.retHTML).insertAfter("#zone"+reponse.zone);
          toastr.success("The route has been edited!");
          $('#editRouteModal').modal('hide');
        }        
      },
      error: function(request, status, error) {
        console.log(error);
      }
    });
  });

  /// Event handler of the click on the full load button unchecking / disabling the others radios. 
  $("#btnradio12").click(function() {
    if($("#btnradio12").prop("checked")) {
      $("#btnradio1, #btnradio2, #btnradio3, #btnradio4, #btnradio5, #btnradio6, #btnradio7, #btnradio8, #btnradio9, #btnradio10, #btnradio11").prop("checked", false);        
      $("#btnradio1, #btnradio2, #btnradio3, #btnradio4, #btnradio5, #btnradio6, #btnradio7, #btnradio8, #btnradio9, #btnradio10, #btnradio11").attr("disabled", true);       
    } else {
      $("#btnradio1, #btnradio2, #btnradio3, #btnradio4, #btnradio5, #btnradio6, #btnradio7, #btnradio8, #btnradio9, #btnradio10, #btnradio11").attr("disabled", false);
    }
  });

  /// Event handler of the click on the button right to the "From cities" input
  $('#btnClearFrom').click(function() {        
    let tmp = $('#trip_additionalCityFrom').val();
    let tmpArr = tmp.split("+");                      
    tmpArr.pop();              
    let final = "";              
    tmpArr.forEach(elem => { final += elem + "+"; });        
    final = final.slice(0, -1);        
    $('#trip_additionalCityFrom').val(final);        
  });

  /// Event handler of the click on the button right to the "To cities" input
  $('#btnClearTo').click(function() {        
    let tmp = $('#trip_additionalCityTo').val();
    let tmpArr = tmp.split("+");                      
    tmpArr.pop();              
    let final = "";              
    tmpArr.forEach(elem => { final += elem + "+"; });
    final = final.slice(0, -1);
    $('#trip_additionalCityTo').val(final);        
  });

  /// On change of country selection
  $('#from_country_select').on('change',function() {
    var country_code = $(this).find('option:selected').val();    
    fromcity(country_code);
    google.maps.event.addDomListener(window, 'load', fromcity);           
  });

  /// CityFrom autocomplete
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

</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsEtLFAR_7CkTeUiXCYUK-lgz44Ix2Xjs&libraries=places&language=en"></script>