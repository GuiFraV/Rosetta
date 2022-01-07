@extends('manager.navbar')

@section('content')

<br>
<div class="jumbotron text-center">
  <h1 class="display-5" style="font-family: Segoe UI;">Manager Dashboard</h1>
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
      <div class="card" >
        <i class="card-img-top bi bi-building text-primary" style="font-size: 3rem; text-align:center; margin-bottom: -5px;"></i>
        <div class="card-body">
          <h5 class="card-title">Partners</h5>
          <p class="card-text">Check the list of your partners and all the details about them.</p>
          <a href="/manager/partners" role="button" class="bi bi-arrow-down-left-circle float-end" style="font-size: 1.8rem;"></a>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <i class="card-img-top bi bi-truck text-primary" style="font-size: 3rem; text-align:center; margin-bottom: -5px;"></i>
        <div class="card-body">
            <h5 class="card-title">Routes</h5>
            <p class="card-text">Check the list of the routes, and add loads and trucks.</p>
            <a href="/manager/trajets" role="button" class="bi bi-arrow-down-left-circle float-end" style="font-size: 1.8rem;"></a>
        </div>
      </div>
    </div>    
  </div>
  <br>
  
  <div class="row">
    <div class="col">    
      <div class="card">
        <i class="card-img-top bi bi-list text-primary" style="font-size: 3rem; text-align:center; margin-bottom: -5px;"></i>
        <div class="card-body">
          <h5 class="card-title">Prospects</h5>
          <p class="card-text">Check the list of prospect, and try to turn them into partners.</p>
          <a href="/manager/prospects" role="button" class="bi bi-arrow-down-left-circle float-end" style="font-size: 1.8rem;"></a>
        </div>
      </div>
    </div>
    <div class="col">    
      <div class="card">
        <i class="card-img-top bi bi-envelope text-primary" style="font-size: 3rem; text-align:center; margin-bottom: -5px;"></i>
        <div class="card-body">
          <h5 class="card-title">Emails</h5>
          <p class="card-text">Manage the emails, and send them to your partner's group.</p>
          <a href="/manager/mails" role="button" class="bi bi-arrow-down-left-circle float-end" style="font-size: 1.8rem;"></a>
        </div>
      </div>
    </div>
  </div>
    
</div>

@endsection