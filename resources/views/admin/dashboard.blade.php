@extends('admin.navbar')

@section('content')
<br>
<div class="jumbotron text-center">
  <h1 class="display-5" style="font-family: Segoe UI;">Administrator Dashboard</h1>
</div>
<br>

<style>
  .card-body{
      margin-top: -19px;
  }
</style>

<div class="container">
  <div class="row">
    <div class="col">
      <div class="card">
        <i class="card-img-top bi bi-person text-primary" style="font-size: 3rem; text-align:center; margin-bottom: -5px;"></i>
        <div class="card-body">
            <h5 class="card-title">User management</h5>
            <p class="card-text">Manage all the users : set them a specific role, edit data about them, or delete them from the database.</p>
            <a href="/admin/managers" role="button" class="bi bi-arrow-down-left-circle float-end" style="font-size: 1.8rem;"></a>
        </div>
      </div>
    </div>
    <div class="col">    
      <div class="card" >
        <i class="card-img-top bi bi-building text-primary" style="font-size: 3rem; text-align:center; margin-bottom: -5px;"></i>
        <div class="card-body">
          <h5 class="card-title">Partners</h5>
          <p class="card-text">Manage the clients and the carriers of the system : create, edit, disable, delete, or reassign to another manager.</p>
          <a href="/admin/partners" role="button" class="bi bi-arrow-down-left-circle float-end" style="font-size: 1.8rem;"></a>
        </div>
      </div>
    </div>    
  </div>
  <br>
  
  <div class="row">
    <div class="col">    
      <div class="card">
        <i class="card-img-top bi bi-clock text-primary" style="font-size: 3rem; text-align:center; margin-bottom: -5px;"></i>
        <div class="card-body">
          <h5 class="card-title">Email's sending Schedule</h5>
          <p class="card-text">Manage the hours of sending of the Emails of the system for each Intergate agency.</p>
          <a href="/admin/horaires" role="button" class="bi bi-arrow-down-left-circle float-end" style="font-size: 1.8rem;"></a>
        </div>
      </div>
    </div>
    <div class="col">    
      <div class="card">
        <i class="card-img-top bi bi-globe2 text-primary" style="font-size: 3rem; text-align:center; margin-bottom: -5px;"></i>
        <div class="card-body">
          <h5 class="card-title">Countries</h5>
          <p class="card-text">Manage the countries used in the system : to add or delete a country from the system.</p>
          <a href="/admin/country" role="button" class="bi bi-arrow-down-left-circle float-end" style="font-size: 1.8rem;"></a>
        </div>
      </div>
    </div>
  </div>
    
</div>

@endsection