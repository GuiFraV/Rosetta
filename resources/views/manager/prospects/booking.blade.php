@extends('manager.navbar')

@section('content')
    <div class="container col-5">
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