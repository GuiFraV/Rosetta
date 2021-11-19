@extends('manager.navbar')

@section('content')

<div class="container">
    <h2>Prospect list</h2>
    <a href="{{ route('manager.prospect.create') }}" role="button" class="float-end btn btn-primary">Add a prospect</a><br><br>
    <table class="table table-hover yajra-datatable">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Origin</th>
                <th scope="col">Type</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">State</th>
                <th scope="col">Creation</th>
                <th scope="col">Deadline</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
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
                {data: 'created_at', name: 'created_at'},
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