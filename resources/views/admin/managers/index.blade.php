@extends('admin.navbar')

@section('content')
@include('admin.managers.modals.edit')
@include('admin.managers.modals.pass')
@include('admin.managers.modals.add')

@if(!empty(Session::get('validationError')))
    <script>toastr.warning("{{ Session::get('validationError') }}");</script>
@endif

@if(!empty(Session::get('creationSuccess')))
    <script>toastr.success("{{ Session::get('creationSuccess') }}");</script>
@endif

@if(!empty(Session::get('updateSuccess')))
    <script>toastr.success("{{ Session::get('updateSuccess') }}");</script>
@endif

<div class="mt-5" style="margin-right: 80px;margin-left: 80px;">
  <div class="row">
    <div class="col-8">
        <a data-bs-toggle="modal" data-bs-target="#addmanagermodal" class="btn btn-success" >Add a manager</a>
    </div>
    {{-- Abandonned search function ? (Useless at the moment because of the low number of managers) 
    <div class="container col-4">
      <div class="container ">
          <form class="d-flex">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"  autocomplete="off"/>
              <br />
              <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
      </div>
    </div> 
    --}}
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
          <td>{{ $object->first_name }}</td>
          <td>{{ $object->last_name }}</td>
          <td>{{ $object->user->email }}</td>
          <td>{{ $object->type }}</td>
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

<script type="text/javascript">
    
  $('input[type="checkbox"]').on('click', function() {
    let statuactive = ($(this).is(':checked',true)) ? 0 : 1;        
    $.ajax({
      type:"GET",
      url:"/admin/manager/activate",
      data:{'user_id':$(this).attr("idcheck") , "statusactive" : statuactive},
      dataType: 'json',
      success:function (data) {
        if (data["active"]==0) {
          toastr.warning("Account has been disabled.");
        } else {
          toastr.success("Account has been activated.");
        }
      }
    });
  });

  $('a[typebtn="editmanagerbtn"]').on('click', function() {
      $("#agency_edit_manager").find('option').attr("selected", false);
      let text = JSON.parse(atob($(this).attr("datamanager")));
      $("#first_name_edit_manager").val(text["first_name"]);
      $("#last_name_edit_manager").val(text["last_name"]);
      $('#agency_edit_manager option[value='+text["agency_id"]+']').attr('selected','selected');
      if(text["type"] == "LM") {
          $("#LM_edit_manager").prop("checked", true);
      } else {
          $("#TM_edit_manager").prop("checked", true);
      }
      $("#email_edit_manager").val(text["user"]["email"]);
      $("#id_manager").val(text["id"]);
      $("#pass_id_manager").val(text["user_id"]);
      console.log(text);
  });

  $('a[typebtn="editpassmanagerbtn"]').on('click', function() {
      var user_id = $(this).attr("user_id");
      $("#pass_id_manager").val(user_id);
  });
    
</script>
@endsection



