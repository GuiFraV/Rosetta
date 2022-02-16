@extends('admin.navbar')

@section('content')

<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Agencies Management</h1>
  </div>
  <br>
  
  <a class="btn btn-primary" id="btnNewAgency" role="button">Create a new agency</a><br><br>  
  
  <table class="table table-striped table-hover yajra-datatable" id="agencyDataTable">
    <thead>
      <tr>
        <th scope="col">Name</th>  
        <th scope="col">Address</th>              
        <th scope="col">Phone</th>
        <th scope="col"></th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody class="align-middle"></tbody>
  </table><br>
</div>

@include('admin.agencies.modals.create')
@include('admin.agencies.modals.edit')
@include('admin.agencies.modals.destroy')

<script>
  $(function () {
    var table = $('#agencyDataTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('admin.agencies.getAgencies') }}",
      order: [[0, 'asc']],
      columns: [
        {data: 'agency_name', name: 'agency_name'},
        {data: 'address', name: 'address'},
        {data: 'office_phone', name: 'office_phone'},        
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
</script>

<script>
  $('#btnNewAgency').on('click', function() {
    $('#createAgencyModal').modal('show');
  });
</script>

@endsection