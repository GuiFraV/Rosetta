@extends('manager.navbar')

@section('content')
<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Partners List</h1>
  </div>
  <br>
   
  <table class="table table-striped table-hover yajra-datatable" id="partnerDataTable">
    <thead>
      <tr>
        <th scope="col">Company</th>  
        <th scope="col">Contact</th>              
        <th scope="col">Origin</th>
        <th scope="col">Phone</th>
        <th scope="col">Email</th>        
        <th scope="col">Created At</th>
        <th scope="col"></th>        
      </tr>
    </thead>
    <tbody class="align-middle"></tbody>
  </table><br>
</div>

@include('manager.partners.modals.show')

<script>
  $(function () {
    var table = $('#partnerDataTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('manager.partners.getPartners') }}",
      order: [[5, 'asc']],
      columns: [
        {data: 'company', name: 'company'},          
        {data: 'name', name: 'name'},        
        {data: 'origin', name: 'origin'},
        {data: 'phone', name: 'phone'},
        {data: 'email', name: 'email'},        
        {data: 'created_at', name: 'created_at'},
        {
          data: 'showBtn', 
          name: 'showBtn', 
          orderable: false, 
          searchable: true
        }
      ]
    });
  });
</script>

@endsection