@extends('admin.navbar')

@section('content')
<br><br>
<div class="container">
    <div id="content" class="content">
        <div class="container">
            <div class="jumbotron text-center">
                <h1 class="display-5">{{$title}}</h1>
            </div>
        </div>
    </div>
</div>
<br><br>
<form class="form-inline" action="{{ route('admin.partners.update',$partner) }}" method="post">
  @csrf
  @method('PATCH')
    <div class="container">
        <div class="row">
          <div class="form-group col-md-4">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name"  name = "name" placeholder="Name"  value="{{$partner->name}}">        
          </div>
          <div class="form-group col-md-4">
            <label for="company">Company</label>
            <input type="text" class="form-control" id="company" name = "company" placeholder="Company" value="{{$partner->company}}">
          </div>
          <div class="form-group col-md-4">
            <label for="origin">Origin</label>
            <input type="text" class="form-control" id="origin"  name = "origin" placeholder="Origin" value="{{$partner->origin}}">        
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-8"> 
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone"  name = "phone" placeholder="Phone" value="{{$partner->phone}}">      
          </div>
          <div class="col-md-4">
            {{-- <label for="type">Type</label> --}}
            {{-- <input type="text" class="form-control" id="type"  name = "type" placeholder="Type"> --}}
            {{-- <select class="form-select" id="type" name = "type" placeholder="Type">
              <option value="Client" >Client</option>
              <option value="Carrier"  >Carrier</option>
            </select> --}}
            <div class="col-md-4 text-center">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="type_partner" id="client" value="client" <?php if($partner->type == "Client"){ echo "checked";}?>>
                <label class="form-check-label" for="client">
                  Client
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="type_partner" id="carrier" value="carrier" <?php if($partner->type == "Carrier"){ echo "checked";}?>>
                <label class="form-check-label" for="carrier">
                  Carrier
                </label>
              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-md-4"> 
            <label for="manager_id">Manager</label>
            <select class="form-select pull-down" type="manager_id" id="manager_id"  name = "manager_id" placeholder="Manager">
              <option value="{{$partner->manager_id}}" selected>{{$partner->manager->name}}</option>
                @foreach($managers as $manager)
              <option value="{{$manager->id}}">{{$manager->name}}</option>
                @endforeach
            </select>
          </div>
          <div class="col-md-8"> 
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email"  name = "email" placeholder="Email" value="{{$partner->email}}">
          </div>
        </div>
        <br>
        <div class="container text-center">
          <div class="col-md-12">
          
            <input class="btn btn-outline-success" style="height:40px;width:150px" type="submit" value="Update" />
        </div>
      </div>
  </form>
                                                        
@endsection
