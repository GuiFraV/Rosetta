@extends('manager.navbar')

@section('content')

    <div class="container">
        <div class="jumbotron text-center">
            <form class="form-inline">
                <h1 class="display-5">{{$title}}</h1>
            </form>
        </div>
    </div>

    <div class="container">
        <nav class="navbar navbar-light bg-light">
              <div class="container col-sm-4">
                <form class="d-flex">                  <br>
                  {{-- <button class="btn btn-outline-success" type="submit">Add Group</button> --}}
                  <a class="btn btn-outline-success" href="/groups/create" type="submit">Add Group</a>

                </form>
              </div>
              
            <div class="container col-sm-4">
            </div>
            
                <div class="container col-sm-4">
                  <form class="d-flex" action="{{ route('groups.index') }}" method="GET" >
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="searchbar" autocomplete="off">
                    <br>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                  </form>
                </div>
        </nav>
        <div class="container">
            <input type="hidden" name="">
            <table id="grouping_table" class="table table-striped">
                <thead>
                    <tr>
                        <th  class="text-center" scope="col">Id</th>
                        <th  class="text-center" scope="col">Name</th>
                        <th  class="text-center" scope="col">Company</th>
                        <th  class="text-center" scope="col">Phone</th>
                        <th  class="text-center" scope="col">Email</th>
                        <th  class="text-center" scope="col">Origin</th>
                        <th  class="text-center" scope="col">Created At</th>
                        <th  class="text-center" scope="col">Updated At</th>
                        <th  class="text-center" scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($partners) && $partners -> count() > 0)
                        @foreach ($partners as $partner)
                        <tr>
                            <td  class="text-center">{{$partner->id}}</td>
                            <td  class="text-center">{{$partner->name}}</td>
                            <td  class="text-center">{{$partner->company}}</td>
                            <td  class="text-center">{{$partner->phone}}</td>
                            <td  class="text-center">{{$partner->email}}</td>
                            <td  class="text-center">{{$partner->origin}}</td>
                            <td  class="text-center">{{$partner->created_at}}</td>
                            <td  class="text-center">{{$partner->updated_at}}</td>
                            <td  class="text-center"  class="text-center">
                                    <a class="btn btn-outline-danger" href="{{ route('groups.deletePartnerFromGroup',[ 'group_id' =>  request()->route('group_id') , 'partner_id' =>  $partner->id]) }}">Delete</a>
                                
                            </td>
                            
                        </tr>
                        @endforeach
                    @endif
                    
                    
                </tbody>
            </table>
        </div>
        
        {{-- <div class="float-right">  
            {{$partners->links('vendor.pagination.custom')}}
        </div> --}}
    </div>
@endsection
