@extends('manager.navbar')

@section('content')
<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Partners List</h1>
  </div>
  <br>
   
  <!-- Add admin restriction for creation after development -->
  <a class="btn btn-primary" id="btnNewPartner" role="button">Create a new partner</a><br><br>  
  <table class="table table-striped table-hover yajra-datatable" id="partnerDataTable">
    <thead>
      <tr>
        <th scope="col">Company</th>  
        <th scope="col">Contact</th>              
        <th scope="col">Origin</th>
        <th scope="col">Type</th>
        <th scope="col">Phone</th>
        <th scope="col">Email</th>        
        <th scope="col">Manager</th>
        <th scope="col">Created At</th>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody class="align-middle"></tbody>
  </table><br>
</div>

@include('manager.partners.modals.create')
@include('manager.partners.modals.show')
@include('manager.partners.modals.edit')
@include('manager.partners.modals.destroy')

<script>
  $(function () {
    var table = $('#partnerDataTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('manager.partners.getPartners') }}",
      order: [[7, 'desc']],
      columns: [
        {data: 'company', name: 'company'},          
        {data: 'name', name: 'name'},        
        {data: 'origin', name: 'origin'},
        {data: 'type', name: 'type'},
        {data: 'phone', name: 'phone'},
        {data: 'email', name: 'email'},
        {data: 'manager_id', name: 'manager_id'},
        {data: 'created_at', name: 'created_at'},
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
</script>

<!-- Si user !admin alors pas ce script -->
<script>
  $('#btnNewPartner').on('click', function() {
    $('#createPartnerModal').modal('show');
  });
</script>

@endsection