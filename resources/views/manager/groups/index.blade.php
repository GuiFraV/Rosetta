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
        <th scope="col">id</th>
        <th scope="col">groupName</th>
        <th scope="col">Created At</th>
        <th scope="col">Updated At</th>
        <th scope="col"></th>
        {{-- <th scope="col"></th>
        <th scope="col"></th> --}}
      </tr>
    </thead>
    <tbody class="align-middle"></tbody>
  </table><br>

  {{-- 
  <div class="container">
    <table id="grouping_table" class="table table-striped">
      <thead>
        <tr>    
          <th scope="col"  class="text-center">Id</th>
          <th scope="col"  class="text-center">Name</th>
          <th scope="col"  class="text-center">Count</th>
          <th scope="col"  class="text-center">Created At</th>
          <th scope="col"  class="text-center">Updated At</th>
          <th scope="col"  class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($groups as $group)    
          <tr>
            <td  class="text-center">{{$group->id}}</td>
            <td  class="text-center">{{$group->groupName}}</td>
            <td  class="text-center">{{count($group->partners)}}</td>                
            <td  class="text-center">{{$group->created_at}}</td>
            <td  class="text-center">{{$group->updated_at}}</td>
            <td  class="text-center">
              <form action="{{ route('manager.groups.update', ['group' => $group->id]) }}" method="POST">
                <a class="btn btn-outline-primary" href="{{ route('manager.groups.edit',$group->id) }}">Edit</a>
                <a class="btn btn-outline-success" href="{{ route('manager.groups.showPartner',$group->id) }}">Show</a>
              </form>
            </td>
          </tr>
        @endforeach            
      </tbody>
    </table>
  </div>
  --}}
        
  {{-- <p class="text-left">
    $@forelse ($group->partners as $partner)
      {{ $partner->name}}
    @empty
        <p>No partner found</p>
    @endforelse
  </p> --}}        
</div>

@include('manager.groups.modals.create')

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
            data: 'testBtn', 
            name: 'testBtn', 
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
