@extends('manager.navbar')

@section('content')
<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Partners List</h1>
  </div>
  <br>
   
  <!-- <a class="btn btn-primary" id="btnNewGroup" role="button">Create a new group</a><br><br>   -->
  <table class="table table-striped table-hover yajra-datatable" id="groupDataTable">
    <thead>
      <tr>
        <th scope="col">Contact</th>
        <th scope="col">Company</th>        
        <th scope="col">Origin</th>
        <th scope="col">Type</th>
        <th scope="col">Phone</th>
        <th scope="col">Email</th>        
        <th scope="col">Manager</th>
        
        <th scope="col">Created At</th>
        <!-- <th scope="col"></th> -->
        <!-- <th scope="col"></th> -->
        <!-- <th scope="col"></th> -->
      </tr>
    </thead>
    <tbody class="align-middle"></tbody>
  </table><br>
</div>

<script>
  $(function () {
    var table = $('#groupDataTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('manager.partners.getPartners') }}",
      order: [[7, 'desc']],
      columns: [
        {data: 'name', name: 'name'},
        {data: 'company', name: 'company'},        
        {data: 'origin', name: 'origin'},
        {data: 'type', name: 'type'},
        {data: 'phone', name: 'phone'},
        {data: 'email', name: 'email'},
        {data: 'manager_id', name: 'manager_id'},
        {data: 'created_at', name: 'created_at'}
        /*
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
        */       
      ]
    });
  });
</script>

@endsection
