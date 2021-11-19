@extends('manager.navbar')

@section('content')
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
<script type="text/javascript">
    function myfunction(){
        var map;
        var start = new google.maps.LatLng(7.434876909631617,80.4424951234613);
        var end = new google.maps.LatLng(7.3178281209262686,80.8735878891028);
        var option ={
            zoom : 10,
            center : start 
        };
        map = new google.maps.Map(document.getElementById('map'),option);
        var display = new google.maps.DirectionsRenderer();
        var services = new google.maps.DirectionsService();
        display.setMap(map);
            var request ={
                origin : start,
                destination:end,
                travelMode: 'DRIVING'
            };
            services.route(request,function(result,status){
                if(status =='OK'){
                    display.setDirections(result);
                }
            });
    }
</script>
<div class="mt-5" style="margin-right: 80px;margin-left: 80px;">
    <div class="row" style="margin-top: 5rem;">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Management of Routes</h2>
            </div>
            <p>
                <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Filter
                </a>
                <form action="{{ route('manager.trajets.create') }}" method="GET">
                    <button class="btn btn-success" type="submit" href="{{ route('manager.trajets.create') }}" name="type" value="load" >
                        Add Load
                    </button>
                    <button class="btn btn-success" type="submit" href="{{ route('manager.trajets.create') }}" name="type" value="truck" >
                        Add Truck
                    </button>
                </form>
                
              </p>
              <div class="collapse" id="collapseExample">
                <div class="card card-body">
                    <form action="{{ route('manager.trajets.index') }}" method="GET">
                        <div class="container col-sm-4">
                          <form class="d-flex" >
                            <input type="date" name="searchbar" style="display: block;    width: 100%;    padding: .375rem .75rem;    font-size: 1rem;    font-weight: 400;    line-height: 1.5;    color: #212529;    background-color: #fff;    background-clip: padding-box;    border: 1px solid #ced4da;    -webkit-appearance: none;    -moz-appearance: none;    appearance: none;    border-radius: .25rem;    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;" value="<?php echo date("Y-m-d");?>">
                            <br>
                            <button class="btn btn-outline-success" type="submit">Search</button>
                            <a class="btn btn-outline-primary" href="/trajets">Reset</a>
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
            <table class="table">
                @foreach ($zones as $item)
                    <tr class="table-light">
                        <th colspan="8">{{$item->zone_name}}</th>
                        <?php  $i = 0 ;?>
                        @foreach ($data as $key)
                        <tr>
                            @if ($key->zone_name == $item->zone_name)
                                @if ($i==0)
                                <tr>
                                    <th>Action</th>
                                    <th>Date of departure</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Distance</th>
                                    <th>Type</th>
                                    <th>Key</th>
                                    <th>Stars</th>
                                </tr>
                                @endif
                                
                                <td> 
                                    <a onclick="duplicate(<?php echo $key->id?>)" ><span style = "color: Dodgerblue;" title="Duplicate" class="fa fa-copy" ></span></a>
                                    @if ($key->type != $type_manager)
                                        <a onclick="duplicate(<?php echo $key->id?>)" ><span style = "color: Dodgerblue;" title="Matcher" class="fa fa-copy" ></span></a>

                                    @endif
                                    <a from_l = "<?php echo $key->from_others?>" to_l = "<?php echo $key->to_others?>" typebtn="openmapps"><span style = "color: Dodgerblue;" title="Open Maps" class="fa fa-map-marked-alt" ></span></a>


                                </td>
                                <td>
                                    <?php  
                                        $orgDate = $key->date_depart;  
                                        $newDate = date("d/m/Y", strtotime($orgDate));  
                                        echo $newDate;  
                                    ?>  
                                </td>
                                <td>{{$key->from_others}}</td>
                                <td>{{$key->to_others}}</td>
                                <td>
                                    @if ($key->distance != 0)
                                        {{(int)(($key->distance)/1000)}} Km
                                    @else
                                        NaN
                                    @endif
                                </td>
                                <td>
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
                                    <td></td>
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
                                
                            @endif
                        </tr>
                        <?php ++$i ?>
                        @endforeach

                    </tr>
                @endforeach
        
            
            </table>
        @endif
          
        
    </div>
    
</div>
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
@endsection
