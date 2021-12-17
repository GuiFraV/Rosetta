@extends('manager.navbar')

@section('content')
<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Groups Management</h1>
  </div>
  <br>        
  
  {{-- <a class="btn btn-primary" id="btnNewGroup" role="button" href="{{ route('manager.groups.create') }}">Create a new group</a><br><br> --}}
  <a class="btn btn-primary" id="btnNewGroup" role="button">Create a new group</a><br><br>

  {{-- 
  <nav class="navbar navbar-light bg-light">
    <div class="container col-sm-4">
      <form class="d-flex">                  
        <br>
        <button class="btn btn-outline-success" type="submit">Add Group</button>
        <a class="btn btn-outline-success" href="{{ route('manager.groups.create') }}" type="submit">Add Group</a>
      </form>
    </div>        
  </nav>
  --}}
  
  <table class="table table-striped table-hover yajra-datatable" id="groupDataTable">
    <thead>
      <tr>
        <th scope="col">Number</th>
        <th scope="col">Name</th>
        <th scope="col">Created At</th>
        <th scope="col">Updated At</th>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody class="align-middle"></tbody>
  </table><br>
</div>

@include('manager.groups.modals.create')
@include('manager.groups.modals.show')
@include('manager.groups.modals.destroy')

<script>
  $(function () {
    var table = $('#groupDataTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('manager.groups.getGroups') }}",
      //order: [[1, 'desc']],
      columns: [
        {data: 'id', name: 'id'},
        {data: 'groupName', name: 'groupName'},
        {data: 'created_at', name: 'created_at'},
        {data: 'updated_at', name: 'updated_at'},        
        {
            data: 'showBtn', 
            name: 'showBtn', 
            orderable: false, 
            searchable: true
        },
        {
            data: 'editBtn', 
            name: 'editBtn', 
            orderable: false, 
            searchable: true
        },
        {
            data: 'deleteBtn', 
            name: 'deleteBtn', 
            orderable: false, 
            searchable: true
        }        
      ]
    });
  });

  $('#btnNewGroup').on('click', function() {
    $.ajax({
      async: true,
      type: "GET",
      url: "groups/openModalNew",
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug JSON response
        // console.log(data);
        if(data === "[]") {
          toastr.error("You don't have any partner to create a new group.");
          return 1;
        } else {
          let options = JSON.parse(data);
          $('.selectpicker').empty();
          // console.log(options);
          options.forEach(row => {            
            $('.selectpicker').append("<option value='"+row['value']+"'>"+row['label']+"</option>");
          });
          $('.selectpicker').selectpicker('refresh');
          $('#createGroupModal').modal('show');
        }
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });
  });
</script>

@endsection
