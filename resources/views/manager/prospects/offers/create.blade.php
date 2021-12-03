@extends('manager.navbar')

@section('content') 
    <div class="container col-6">
        <h2>Add a new offer</h2><br>
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
        <form method="post" action="{{ route('manager.offer.store') }}">
            @csrf
            <input type="hidden" name="id_prospect" value="{{ $_GET['prospect'] }}">
            <div class="row">
                <div class="col">
                    <label for="cityFrom" class="form-label">From</label>
                    <input type="text" class="form-control" name="cityFrom" required>
                </div>
                <div class="col">
                    <label for="cityTo" class="form-label">To</label>
                    <input type="text" class="form-control" name="cityTo" required>
                </div>
                <div class="col">
                    <label for="offer" class="form-label">Offer</label>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-label="Amount in euros" name="offer">
                        <span class="input-group-text">€</span>
                    </div>
                </div>
            </div><br/>
            <div class="form-floating">
                <textarea class="form-control" placeholder="Feedback on the offer" name="comment" style="height: 200px;"></textarea>
                <label for="comment">What can you say about the offer?</label>
            </div><br>
            <a href="{{ route('manager.prospect.show', $_GET['prospect']) }}" class="btn btn-danger">Return</a>
            <button type="submit" class="btn btn-primary float-end">Create</button>
        </form>
    </div>
@endsection