@extends('admin.navbar')

@section('content')
    <div class="container">
        @include('inc.header')
        @include('message.opMessage')
        <nav class="navbar navbar-light bg-light">
            <div class="container col-sm-4">
            </div>
            <div class="container col-sm-4">
            </div>
            
                <div class="container col-sm-4">
                    <form class="d-flex" action="{{ route('clients.index') }}" method="GET">
                    <input class="form-control me-4" type="search" placeholder="Search" aria-label="Search" name="searchbar" autocomplete="off">
                    <br>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                  </form>
                </div>
  
        </nav> <br>
        <div class="container">
            <table id="partnering_table" class="table table-striped">
                <thead>
                    <tr>
                        <th style="font-size: 80%"  scope="col">Name</th>
                        <th style="font-size: 80%"  scope="col">Company</th>
                        <th style="font-size: 80%"  scope="col">Phone</th>
                        <th style="font-size: 80%"  scope="col">Email</th>
                        <th style="font-size: 80%"  scope="col">Origin</th>
                        <th style="font-size: 80%"  scope="col">Groups</th>
                        <th style="font-size: 80%"  scope="col">Created At</th>
                        {{-- <th style="font-size: 80%"  scope="col">Updated At</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($partners as $partner)
                    <tr>
                        <td style="font-size: 80%" >{{$partner->name}}</td>
                        <td style="font-size: 80%" >{{$partner->company}}</td>
                        <td style="font-size: 80%" >{{$partner->phone}}</td>
                        <td style="font-size: 80%" >{{$partner->email}}</td>
                        <td style="font-size: 80%" >{{$partner->origin}}</td>
                        <td style="font-size: 80%" >
                    
                            <ul>
                                @foreach ($partner->groups as $group)
                                    <li id={{$group->id}}>{{$group->groupName}}</li>
                                    @endforeach
                            </ul>
                        
                        </td>
                        <td style="font-size: 80%" >{{$partner->created_at}}</td>
                        {{-- <td style="font-size: 80%" >{{$partner->updated_at}}</td> --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="float-right">  
        {{$partners->links('vendor.pagination.custom')}}
      </div>
@endsection
