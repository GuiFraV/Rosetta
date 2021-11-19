@extends('admin.navbar')

@section('content')
@include('admin.managers.modals.edit')
@include('admin.managers.modals.pass')
@include('admin.managers.modals.add')

<div class="container col-md-8" style="margin-top: 2%; display:none;" id="messagealert"  >
    <div class="alert alert-danger">
        Account has been Activated
    </div>
</div>
@if ($message = Session::get('succesupdate'))
    <div class="container col-md-8" style="margin-top: 2%;" id="messageupdatealert"  >
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    </div>
@endif
@if ($message = Session::get('succesadd'))
    <div class="container col-md-8" style="margin-top: 2%;" id="messageaddalert"  >
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    </div>
@endif
<script>
    setTimeout(function () {
        document.getElementById('messageupdatealert').style.display='none';
    }, 1000);
        
</script>
<script>
    setTimeout(function () {
        document.getElementById('messageaddalert').style.display='none';
    }, 1000);
        
</script>

<div class="mt-5" style="margin-right: 80px;margin-left: 80px;">
    <div class="row">
        <div class="col-8">
            <a data-bs-toggle="modal" data-bs-target="#addmanagermodal" class="btn btn-success" >Add a manager</a>
        </div>

        {{-- <div class="container col-4">
            <div class="container ">
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"  autocomplete="off"/>
                    <br />
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div> --}}
    </div>
    <br>
    <table class="table">
        <thead>
        <tr>
            <th>First name</th>
            <th>Last name</th>
            <th>Email</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($objects as $object)
                <tr>
                    <td>{{$object->first_name}}</td>
                    <td>{{$object->last_name}}</td>
                    <td>{{$object->user->email}}</td>
                    <td>{{$object->type}}</td>
                    <td>
                        
                        
                        
                        <div class="form-check form-switch">
                            <a href="#" id="editmanager" data-bs-toggle="modal" data-bs-target="#editmanagermodal" typebtn= "editmanagerbtn" datamanager={{base64_encode($object)}}><span title="Edit" class="fa fa-edit"></span></a>
                            @if ($object->user->active == 1)
                                <input class="form-check-input" name="activecheckbox" type="checkbox" id="activecheckbox" title="Desactivate" value={{$object->user->active}} idcheck={{$object->user->id}} checked>
                            @else
                                <input class="form-check-input" name="activecheckbox" type="checkbox" id="activecheckbox" title="Activate" value={{$object->user->active}} idcheck={{$object->user->id}}>
                            @endif
                            <a href="#" id="editpassmanager" data-bs-toggle="modal" data-bs-target="#editpassmanagermodal" typebtn= "editpassmanagerbtn" user_id={{$object->id}}><span title="Password Update" class="fa fa-key"></span></a>

                        </div>
                        
                    </td>
                </tr> 
            @endforeach
            
        </tbody>
    </table> 
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script type="text/javascript">
    
    $(function( $ ){
        $('input[type="checkbox"]').on('click',function() {
            var statuactive = 0;
            if ($(this).is(':checked',true)){
                var statuactive = 0;
            }else{
                var statuactive = 1;
            }
            
            $.ajax({
                type:"GET",
                url:"/admin/manager/activate",
                data:{'user_id':$(this).attr("idcheck") , "statusactive" : statuactive},
                dataType: 'json',
                success:function (data) {
                    if (data["active"]==0){
                        $("#messagealert").html('<div class="alert alert-danger">Account has been Desactivated</div>');
                        
                    }else{
                        $("#messagealert").html('<div class="alert alert-success">Account has been Activated</div>');
                    }
                    $("#messagealert").show();
                    
                    setTimeout(function () {
                        $("#messagealert").hide();
                    }, 1000);
                }
            });
        });

        $('a[typebtn="editmanagerbtn"]').on('click',function() {
            $("#agency_edit_manager").find('option').attr("selected",false) ;
            var text = JSON.parse(atob($(this).attr("datamanager")))
            $("#first_name_edit_manager").val(text["first_name"]);
            $("#last_name_edit_manager").val(text["last_name"]);
            $('#agency_edit_manager option[value='+text["agency_id"]+']').attr('selected','selected');
            if(text["type"] == "LM"){
                $("#LM_edit_manager").prop("checked", true);
            }else {
                $("#TM_edit_manager").prop("checked", true);
            }
            $("#email_edit_manager").val(text["user"]["email"]);
            $("#id_manager").val(text["id"]);
            $("#pass_id_manager").val(text["user_id"]);
            console.log(text);
        });
        $('a[typebtn="editpassmanagerbtn"]').on('click',function() {
            var user_id = $(this).attr("user_id");
            $("#pass_id_manager").val(user_id);
        });

        
        
        
        
    });
    
    
   
</script>
@endsection



