@extends('admin.navbar')

@section('content')

@include('admin.horaires.modals.add')

<div class="container col-md-8" style="margin-top: 2%;" id="messageaddalert"  >
    @if ($message = Session::get('succesadd'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif  
</div>
<script>
    setTimeout(function () {
        document.getElementById('messageaddalert').style.display='none';
    }, 1000);
        
</script>
<div class="mt-5" style="margin-right: 80px;margin-left: 80px;">
    <div class="row">
        <div class="col-8">
            <a data-bs-toggle="modal" data-bs-target="#addhoraire" class="btn btn-success"  >Add a time</a>
        </div>
    </div>
    <br>
     <div style="width: 100%; display: table;">
        <div style="display: table-row; height: 100px;">
            <div style="width: 50%; display: table-cell; position: relative;  ">
                <div class="modal-body" style="position: absolute; transform: translate(0, 40%);">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Agency</label>
                        <select  class="form-control" id="agency_edit_hour" name="agency_edit_hour">
                            @foreach ($agencies as $item)
                                <option value="{{$item->id}}">{{$item->agency_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Type of Manager :</label>
                        <input class="form-check-input" type="radio" name="manager_edit_radios" id="LM_edit_hour" value="LM" checked>
                        <label class="form-check-label" for="LM_edit_hour">
                            Logistic Manager
                        </label>
                        <input class="form-check-input" type="radio" name="manager_edit_radios" id="TM_edit_hour" value="TM">
                        <label class="form-check-label" for="TM_edit_hour">
                            Transport Manager
                        </label>
                    </div>
                    <br>
                    <div class="form-group">
                        <a class="btn btn-primary" id="searchhour">Search</a>
                    </div>
                </div> 
            </div>
            <div style=" display: table-cell; position: relative;  ">
                <div class="modal-body" style="transform: translate(0, 40%);">
                    <table class="table table-primary" style="text-align: center">
                        <thead>
                        <tr>
                            <th scope="col">Hour of sending</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($horaires as $item)
                                <tr scope="row">
                                    <td >{{$item->horaire_text}}</td>
                                    <td>
                                        <a class="fa fa-edit" title="edithour"></a>   
                                
                                        <a class="fa fa-trash-alt" title="deletehour"></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script type="text/javascript">
    
    $(function( $ ){
        $('a[id="searchhour"]').on('click',function() {
           console.log("a");
           var agency_id = $('#agency_edit_hour').find(":selected").val();
           var agency_id = $("#manager_edit_radios").val();
           $.ajax({
                type:"GET",
                url:"/admin/searchhour",
                data:{'user_id':$(this).attr("idcheck") , "statusactive" : statuactive},
                dataType: 'json',
                success:function (data) {
                    console.log(data)
                    
                }
            });

            
            
        });

        
    });
    
    
   
</script>
@endsection



