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
    <h2>My marketing search</h2>
    <a href="{{ route('manager.marketingsearch.create') }}" role="button" class="float-end btn btn-primary">Add a marketing search</a><br><br>

    @if(!empty(Session::get('created')))
        <script>toastr.success('{{ Session::get('created') }}');</script>
    @elseif(!empty(Session::get('edited')))
        <script>toastr.warning('{{ Session::get('edited') }}');</script>
    @elseif(!empty(Session::get('deleted')))
        <script>toastr.danger('{{ Session::get('deleted') }}');</script>
    @endif

    <table class="table table-striped table-hover yajra-datatable disable-select">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Origin</th>
                <th scope="col">Type</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Creation</th>                
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody class="align-middle"></tbody>
    </table><br>
    <input type="text" id="mailCopy" style="display: none;">
</div>

<script type="text/javascript">
    
    $(function() {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('manager.marketingsearch.getMarketingSearches') }}",
            order: [[5, 'desc']],
            columns: [
                {data: 'name', name: 'name'},
                {data: 'country', name: 'country'},
                {data: 'type', name: 'type'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'created_at', name: 'created_at'},                
                {
                    data: 'editBtn', 
                    name: 'editBtn', 
                    orderable: false, 
                    searchable: true,
                },
                {
                    data: 'transformBtn', 
                    name: 'transformBtn', 
                    orderable: false, 
                    searchable: true,
                },
                {
                    data: 'deleteBtn', 
                    name: 'deleteBtn', 
                    orderable: false, 
                    searchable: true,
                }
            ]
        });
    });
    
</script>

@endsection