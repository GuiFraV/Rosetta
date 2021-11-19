@extends('admin.navbar')

@section('content')


<div class="container">
    <nav class="navbar navbar-light bg-light">
        <div class="container col-sm-4">
            <form class="d-flex">
                <br />
                <a class="btn btn-outline-success" href="{{ route('admin.partners.create') }}" type="submit">Add partner</a>
            </form>
        </div>
        <div class="container col-sm-4"></div>

        <div class="container col-sm-4">
            <form class="d-flex" action="{{ route('admin.partners.index') }}" method="GET">
                <input class="form-control me-4" type="search" placeholder="Search" aria-label="Search" name="searchbar" autocomplete="off" />
                <br />
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </nav>
    <br>
    {{-- <div class="container"> --}}
        <table id="partnering_table" class="table table-striped">
            <thead>
                <tr>
                    <th style="font-size: 78%" scope="col">Name</th>
                    <th style="font-size: 78%" scope="col">Company</th>
                    <th style="font-size: 78%" scope="col">Phone</th>
                    <th style="font-size: 78%" scope="col">Email</th>
                    <th style="font-size: 78%" scope="col">Origin</th>
                    <th style="font-size: 78%" scope="col">Type</th>
                    <th style="font-size: 78%" scope="col">Manager</th>
                    {{-- <th style="font-size: 78%" scope="col">Groups</th> --}}
                    <th style="font-size: 78%" scope="col">Status</th>
                    {{-- <th scope="col">Created At</th>
                    <th scope="col">Updated At</th> --}}
                    <th style="font-size: 78%" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($partners as $partner)
                <tr>
                    <td style="font-size: 78%" >{{$partner->name}}</td>
                    <td style="font-size: 78%" >{{$partner->company}}</td>
                    <td style="font-size: 78%" >{{$partner->phone}}</td>
                    <td style="font-size: 78%">{{$partner->email}}</td>
                    <td style="font-size: 78%" >{{$partner->origin}}</td>
                    <td style="font-size: 78%" >{{$partner->type}}</td>
                    <td style="font-size: 78%" >{{$partner->manager->name}}</td>
                    {{-- <td style="font-size: 78%" >
                    
                        <ul>
                            @foreach ($partner->groups as $group)
                                <li id={{$group->id}}>{{$group->groupName}}</li>
                                @endforeach
                        </ul>
                    
                    </td> --}}
                    <td style="font-size: 78%"  class="text-center">
                        @if ($partner->status == 1)
                            🟢
                        @else
                            🔴
                        @endif
                    </td>
                    {{-- <td>{{$partner->created_at}}</td>
                    <td>{{$partner->updated_at}}</td> --}}
                    <td style="font-size: 78%" >
                        <form action="{{ route('admin.partners.partnerStatus',$partner->id) }}" method="POST">
                            <a style="font-size: 90%"  class="btn btn-outline-primary" href="{{ route('admin.partners.edit',$partner->id) }}">Edit</a>
                            @csrf
                            {{-- @method('PATCH') --}}
                            {{-- <button class="btn btn-outline-danger" type="submit">Delete</button> --}}
                            @if ($partner->status == 1)
                                <button style="font-size: 90%"  class="btn btn-outline-danger" type="submit">Off</button>
                            @elseif ($partner->status == 0)
                                <button style="font-size: 90%"  class="btn btn-outline-success" type="submit">On</button>
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    {{-- </div> --}}
</div>

<div class="float-right">
    {{$partners->links('vendor.pagination.custom')}}
</div>
@endsection
