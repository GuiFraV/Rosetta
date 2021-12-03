@extends('layouts.app')

@section('content')
    @if (getManagerId() != $tracking->actor && Auth::user()->role_id != 1) 
        {{ app()->call('App\Http\Controllers\ProspectController@index'); }}
    @endif
    <?php $prospect = getProspectById($tracking->id_prospect); ?>
    <div class="container col-6">
        <h2>Edit an existing history</h2><br>
        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <p>Whooooops! Something went wrong.</p><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="{{ route('tracking.update', $tracking->id) }}">
            @csrf    
            @method('put')
            <h5>Prospect archive done the {{ $tracking->updated_at }} by {{ getManagerName($tracking->actor, "all") }}</h5>
            <div class="row">
                <div class="col">
                    <div class="form-floating">
                        <textarea class="form-control" placeholder="Feedback on the prospection..." name="comment" maxlength="255" style="height: 110px;">{{$tracking->comment}}</textarea>
                        <label for="comment">Please give a detailed feedback on your experience with this prospect...</label>
                    </div>
                </div>
            </div><br>
            <a href="{{ route('prospect.show', $prospect->id) }}" class="btn btn-danger">Return</a>
            <button type="submit" class="btn btn-primary float-end">Edit history</button>            
        </form>
    </div>
@endsection