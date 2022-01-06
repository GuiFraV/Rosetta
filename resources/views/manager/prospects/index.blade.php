@extends('manager.navbar')

@section('content')

<style>
    .disable-select {
      -webkit-user-select: none;  
      -moz-user-select: none;    
      -ms-user-select: none;      
      user-select: none;
    }
</style>

<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Prospect list</h1>
  </div>
  <br>

    <a href="{{ route('manager.prospect.create') }}" role="button" class="btn btn-primary">Add a prospect</a><br><br>

    @if(!empty(Session::get('archived')))
        <script>toastr.warning('{{ Session::get('archived') }}');</script>
    @elseif(!empty(Session::get('validated')))
        <script>toastr.success('{{ Session::get('validated') }}');</script>
    @elseif(!empty(Session::get('deleted')))
        <script>toastr.warning('{{ Session::get('deleted') }}');</script>
    @endif

    <table class="table table-striped table-hover yajra-datatable disable-select">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Origin</th>
                <th scope="col">Type</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">State</th>
                <th scope="col">Manager</th>
                <th scope="col">Creation</th>
                <th scope="col">Deadline</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody class="align-middle">
        </tbody>
    </table><br>
    <div class="float-end">
        {{-- <a href="prospect/marketingsearch" role="button" class="bi bi-search">My marketing search</a> --}}
        <a href="prospects/faq" role="button" class="bi bi-question">F.A.Q</a>
    </div>
    <input type="text" id="mailCopy" style="display: none;">
</div>

<script type="text/javascript">
    $(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('manager.prospects.getProspects') }}",
            order: [[7, 'desc']],
            columns: [
                {data: 'name', name: 'name'},
                {data: 'country', name: 'country'},
                {data: 'type', name: 'type'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'state', name: 'state'},
                {data: 'actor', name: 'actor'},
                {data: 'created_at', name: 'created_at'},
                {data: 'deadline', name: 'deadline'},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: true
                },
                {
                    data: 'counter', 
                    name: 'counter', 
                    orderable: true, 
                    searchable: true
                },
            ]
        });
    });
</script>

@endsection