@extends('user.navbar')

@section('content')
<div class="card" style="margin-top: 80px;margin-right: 80px;margin-left: 80px;">
    <div class="card-header">
      Info
    </div>
    <div class="card-body">
      <h5 class="card-title">Welcome {{{ Auth::user()->name}}}</h5>
      <p class="card-text">to the administrator dashboard</p>
    </div>
  </div>

@endsection