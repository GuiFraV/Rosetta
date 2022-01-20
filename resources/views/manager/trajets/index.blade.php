@extends('manager.navbar')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<?php
    function secondsToTime($seconds_time)
    {
        if ($seconds_time < 24 * 60 * 60) {
            return gmdate('H:i:s', $seconds_time);
        } else {
            $hours = floor($seconds_time / 3600);
            $minutes = floor(($seconds_time - $hours * 3600) / 60);
            $seconds = floor($seconds_time - ($hours * 3600) - ($minutes * 60));
            return "$hours:$minutes:$seconds";
        }
    }
?>

@if(!empty(Session::get('validationError')))
    <script>toastr.warning('{{ Session::get('validationError') }}');</script>
@elseif(!empty(Session::get('created')))
    <script>toastr.success('{{ Session::get('created') }}');</script>
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
    <h1 class="display-5" style="font-family: Segoe UI;">Routes List</h1>
  </div>
  <br>

  <div class="col-lg-12 margin-tb">
    <p>        
      <a class="notification btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" >
        <span>Filter</span>
        @if ($type_search!="all")
          <span class="badge">1</span>
        @endif
      </a>          
      @if ($type_search!="all")
        <a class="btn btn-outline-primary" href="/manager/trajets">Reset</a>
      @endif
      <form action="{{ route('manager.trajets.create') }}" method="GET">
        @if(getManagerType() == "TM")
          <button class="btn btn-success" type="submit" href="{{ route('manager.trajets.create') }}" name="type" value="load">Add Load</button>
          <button class="btn btn-secondary" type="button" onclick="getRoutes();">Copy the Trucks</button>
        @elseif(getManagerType() == "LM")
          <button class="btn btn-success" type="submit" href="{{ route('manager.trajets.create') }}" name="type" value="truck">Add Truck</button>
          <button class="btn btn-secondary" type="button" onclick="getRoutes();">Copy the Loads</button>
        @endif          
        <button class="btn btn-danger float-end" type="button" onclick="location.reload();">Refresh</button>  
        <!-- <button class="btn btn-primary float-end" type="button" style="margin-right: 4px;" onclick="duplicateAll()">Duplicate All</button>         -->
      </form>
    </p>
    <div class="collapse" id="collapseExample">
      <div class="card card-body">
        <form action="{{ route('manager.trajets.index') }}" method="GET">
          <div class="container col-sm-4">
            <form class="d-flex"  >
              {{-- <input type="date" name="searchbar" style="display: block;    width: 100%;    padding: .375rem .75rem;    font-size: 1rem;    font-weight: 400;    line-height: 1.5;    color: #212529;    background-color: #fff;    background-clip: padding-box;    border: 1px solid #ced4da;    -webkit-appearance: none;    -moz-appearance: none;    appearance: none;    border-radius: .25rem;    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;" value=""> --}}
              <select name="managerid" style="display: block;    width: 100%;    padding: .375rem .75rem;    font-size: 1rem;    font-weight: 400;    line-height: 1.5;    color: #212529;    background-color: #fff;    background-clip: padding-box;    border: 1px solid #ced4da;    -webkit-appearance: none;    -moz-appearance: none;    appearance: none;    border-radius: .25rem;    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;">
                <option value="test">Select a manager</option>
                @foreach (getallmanagers() as $item)
                  <option value="{{$item->id}}">{{$item->first_name}} {{$item->last_name}}</option>
                @endforeach
              </select>
              <br>
              <button class="btn btn-outline-success" type="submit">Search</button>
              <a class="btn btn-outline-primary" href="/manager/trajets">Reset</a>
            </form>
          </div>                      
        </form>
      </div>
    </div>
    <br>
  </div>
  @if (json_decode($data) == [])
    <div class="container text-center">
      <h1>Oops!</h1>
      <h2>404 Not Found</h2>
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
        <th style="font-size: 85%"></th>
        <th style="font-size: 85%"></th>
      </tr>      
      @foreach ($zones as $item)
        <tr class="table-light">
          <th colspan="11" style="text-align: center">{{ $item->zone_name }}</th>            
          @foreach ($data as $key)
            @if ($key->zone_name == $item->zone_name)
              <tr id="ln{{ $key->id }}">
                <td>                                         
                  <a href="#" from_l="{{ $key->from_others }}" to_l="{{ $key->to_others }}" typebtn="openmapps"><span style="color: Dodgerblue;" title="Open Maps" class="fa fa-map-marked-alt" ></span></a>
                </td>
                <td style="font-size: 75%">{{ getManagerName($key->manager_id, "") }}</td>
                <td style="font-size: 75%">{{ date('d-m-Y H:i', strtotime($key->date_depart)) }}</td>
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
                <td>
                  @if ($key->visible === 0 && $key->manager_id === getManagerId())                    
                    <a role="button" class="bi bi-node-plus text-primary" style="font-size: 1.4rem;" id="buttonDuplicate" title="Duplicate" onclick="duplicate(this, {{ $key->id }})"></a>
                  @elseif ($key->visible > 0 && $key->manager_id === getManagerId())
                    <a role="button" class="bi bi-node-minus text-danger" style="font-size: 1.4rem;" id="buttonUnduplicate" title="Cancel Duplication" onclick="unduplicate(this, {{ $key->id }})"></a>
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

@include('manager.trajets.modals.destroy')

<script type="text/javascript">      
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
        // navigator.clipboard.writeText(copyText);
        // var myFile = new File([copyText], "Routes.txt", {type: "text/plain;charset=utf-8"});
        // saveAs(myFile);        
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
