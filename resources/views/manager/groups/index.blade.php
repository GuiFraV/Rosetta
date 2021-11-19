@extends('manager.navbar')

@section('content')
    <div class="container">
        
        <nav class="navbar navbar-light bg-light">
              <div class="container col-sm-4">
                <form class="d-flex">                  <br>
                  {{-- <button class="btn btn-outline-success" type="submit">Add Group</button> --}}
                  <a class="btn btn-outline-success" href="{{ route('manager.groups.create') }}" type="submit">Add Group</a>

                </form>
              </div>
              
            <div class="container col-sm-4">
            </div>
            
                <div class="container col-sm-4">
                  <form class="d-flex" action="{{ route('manager.groups.index') }}" method="GET" >
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="searchbar" autocomplete="off">
                    <br>
                    <button class="btn btn-outline-success" type="submit">Search</button>
                  </form>
                </div>
        </nav>
        <br>
        <div class="container">
            <table id="grouping_table" class="table table-striped">
                <thead>
                    <tr>
                        
                        <th scope="col"  class="text-center">Id</th>
                        <th scope="col"  class="text-center">Name</th>
                        <th scope="col"  class="text-center">Count</th>
                        <th scope="col"  class="text-center">Created At</th>
                        <th scope="col"  class="text-center">Updated At</th>
                        <th scope="col"  class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $group)
                        
                    <tr>
                        <td  class="text-center">{{$group->id}}</td>
                        <td  class="text-center">{{$group->groupName}}</td>
                        <td  class="text-center">{{count($group->partners)}}</td>
                        
                        <td  class="text-center">{{$group->created_at}}</td>
                        <td  class="text-center">{{$group->updated_at}}</td>
                        <td  class="text-center">
                            {{-- <form action="{{ route('manager.groups.update', ['group' => $group->id]) }}" method="POST">
                                <a class="btn btn-outline-primary" href="{{ route('manager.groups.edit',$group->id) }}">Edit</a>
                                <a class="btn btn-outline-success" href="{{ route('manager.groups.showPartner',$group->id) }}">Show</a>
                            </form> --}}
                        </td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
        {{-- <p class="text-left">
            $@forelse ($group->partners as $partner)
                {{ $partner->name}}
            @empty
                <p>
                    No partner found
                </p>
            @endforelse
        </p> --}}
        
    </div>
@endsection
