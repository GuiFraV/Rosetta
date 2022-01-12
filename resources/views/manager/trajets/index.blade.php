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
<div id="map">
</div>
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
<br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Routes List</h1>
  </div>
  <br>
<div class="mt-5" style="margin-right: 110px;margin-left: 110px;">
    <div class="row" style="margin-top: 5rem;">
        <div class="col-lg-12 margin-tb">
            <p>
                <script type="text/javascript">
                    function ffffffff() {
                    /* Get the text field */
                        var copyText = `<?php echo ($test); ?>`;
                        navigator.clipboard.writeText(copyText);
                        var myFile = new File([copyText], "Routes.txt", {type: "text/plain;charset=utf-8"});
                        saveAs(myFile);
                    }
                </script>
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
                    <button class="btn btn-success" type="submit" href="{{ route('manager.trajets.create') }}" name="type" value="load" @if ($type_manager == "LM") disabled @endif >
                        Add Load
                    </button>
                    <button class="btn btn-success" type="submit" href="{{ route('manager.trajets.create') }}" name="type" value="truck" @if ($type_manager == "TM") disabled @endif>
                        Add Truck
                    </button>
                    <button type="button" onclick="ffffffff();">Click to Save</button>
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
            <table class="table" >
                <tr>
                    <th style="font-size: 85%">Action</th>
                    <th style="font-size: 85%">Manager</th>
                    <th style="font-size: 85%">Date of departure</th>
                    <th style="font-size: 85%">From</th>
                    <th style="font-size: 85%">To</th>
                    <th style="font-size: 85%">Distance</th>
                    <th style="font-size: 85%">Type</th>
                    <th style="font-size: 85%">Key</th>
                    <th style="font-size: 85%">Stars</th>
                    <th style="font-size: 85%">Delete</th>
                </tr>
                @foreach ($zones as $item)
                    <tr class="table-light">
                        <th colspan="9" style="text-align: center">{{$item->zone_name}}</th>
                        <?php  $i = 0 ;?>
                        @foreach ($data as $key)
                        <tr id="ln{{ $key->id }}">
                            @if ($key->zone_name == $item->zone_name)
                                @if ($i===0)
                                
                                @endif
                                
                                <td> 
                                    {{-- <a onclick="duplicate(<?php echo $key->id?>)" ><span style = "color: Dodgerblue;" title="Duplicate" class="fa fa-copy" ></span></a>
                                    @if ($key->type != $type_manager)
                                        <a onclick="duplicate(<?php echo $key->id?>)" ><span style = "color: Dodgerblue;" title="Matcher" class="fa fa-copy" ></span></a>

                                    @endif --}}
                                    <a href = "#" from_l = "<?php echo $key->from_others?>" to_l = "<?php echo $key->to_others?>" typebtn="openmapps"><span style = "color: Dodgerblue;" title="Open Maps" class="fa fa-map-marked-alt" ></span></a>


                                </td>
                                <td style="font-size: 75%">{{ getManagerName($key->manager_id, "") }}</td>
                                <td style="font-size: 75%">
                                    <?php  
                                        $orgDate = $key->date_depart;  
                                        $newDate = date("d/m/Y", strtotime($orgDate));  
                                        echo $newDate;  
                                    ?>  
                                </td>
                                <td style="font-size: 75%">{{$key->from_others}}</td>
                                <td style="font-size: 75%">{{$key->to_others}}</td>
                                <td style="font-size: 75%">
                                    @if ($key->distance != 0)
                                        {{(int)(($key->distance)/1000)}} Km
                                    @else
                                        NaN
                                    @endif
                                </td>
                                <td style="font-size: 75%">
                                @if ($key->vans != 0)
                                    {{$key->vans}} <span class="fa fa-car"  style="align-self: center"></span>
                                @endif
                                @if (strval($key->full_load) == "1")
                                    FL
                                @endif
                                @if ($key->used_cars == 1)
                                    :UC 
                                @endif
                                </td>
                                @if ($key->key == 1)
                                    <td><span class="fa fa-key"  style="align-self: center"></span></td>
                                @else
                                    <td style="font-size: 75%"></td>
                                @endif
                                
                                
                                <td>
                                    @if ($key->stars == 1)
                                        <span class="far fa-star"  style="align-self: center" title="*"></span>
                                    @endif
                                    @if ($key->stars == 2)
                                        <span class="fas fa-star-half-alt"  style="align-self: center" title="**"></span>
                                    @endif
                                    @if ($key->stars == 3)
                                        <span class="fas fa-star"  style="align-self: center" title="***"></span>
                                    @endif
                                </td>

                                {{-- New delete button --}}
                                <td>
                                  <a role="button" class="bi bi-trash text-danger" style="font-size: 1.4rem;" onclick="$('#destroyModal').modal('show'); $('#destroyedId').val({{ $key->id }});"></a>
                                </td>
                                
                            @endif
                            <?php ++$i ?>
                        </tr>
                        @endforeach

                    </tr>
                @endforeach
        
            
            </table>
        @endif
          
        
    </div>
    
</div>

@include('manager.trajets.modals.destroy')

<script type="text/javascript">
    
    function duplicate(el)
    {
        $(document).ready(function(){
        $.ajax({
            url:"/manager/duplicate",
            type:"GET",
            data:{'trajet_id':el},
            success:function (data) {
                location.reload();
            }
        });
       
    });
    }
    $('a[typebtn="openmapps"]').click(function() {

        window.open('https://www.google.com/maps/dir/' + $(this).attr("from_l") +'/' + $(this).attr("to_l"));
        
    });
    
    
   
</script>
<script>

    
    
</script>
@endsection
