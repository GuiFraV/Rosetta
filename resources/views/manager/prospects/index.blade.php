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
    <h2>Prospect list</h2>
    <a href="{{ route('manager.prospect.create') }}" role="button" class="float-end btn btn-primary">Add a prospect</a><br><br>

    @if(!empty($archived))
        <script>toastr.warning('{{ $archived }}');</script>
    @elseif(!empty($validated))
        <script>toastr.success('{{ $validated }}');</script>
    @elseif(!empty($deleted))
        <script>toastr.warning('{{ $deleted }}');</script>
    @endif

    <table class="table table-hover yajra-datatable disable-select">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Origin</th>
                <th scope="col">Type</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">State</th>
                <th scope="col">Actor</th>
                <th scope="col">Creation</th>
                <th scope="col">Deadline</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table><br>
    <a href="prospects/faq" role="button" class="float-end bi bi-question">F.A.Q</a>
    <input type="text" id="mailCopy" style="display: none;">
</div>

<script type="text/javascript">
    $(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('manager.prospects.getProspects') }}",
            order: [ [6, 'desc'] ],
            columns: [
                {data: 'name', name: 'name'},
                {data: 'country', name: 'country'},
                {data: 'type', name: 'type'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'state', name: 'state'},
                {data: 'actor', name: 'actor'},
                {data: 'created_at', name: 'created_at'},
                // {data: 'updated_at', name: 'updated_at'},
                {data: 'deadline', name: 'deadline'},
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: true, 
                    searchable: true
                },
            ]
        });
    });
</script>

@endsection