@extends('manager.navbar')

@section('content')
    <div class="container col-5">
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
        <form action="{{ route('manager.prospect.book', $id) }}" method="post">
            @csrf
            @method('put')
            <div class="row">
                <div class="col">
                    <h3>Book a prospect</h3><br>
                    <p>Do you want to book this prospect?</p>
                    <p>If you accept you will have <b>two weeks</b> to try to turn this prospect into a Client or a Carrier.</p>
                    <p>Please don't forget to add the offers you make in order to contribute to an effective traceability.</p><br>
                    <div class="float-end">
                        <a href="{{url()->previous()}}" role="button" class="btn btn-danger">Cancel</a>
                        <button type="submit" class="btn btn-primary">Let's try!</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection