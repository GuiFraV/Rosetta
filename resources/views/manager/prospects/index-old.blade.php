@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Prospect list</h2>
        <a href="{{ route('prospect.create') }}" role="button" class="float-end btn btn-primary">Add a prospect</a><br><br>
        <table class="table table-hover" style="font-size: 95%">
            <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Origin</th>
                    <th scope="col">Type</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Available</th>
                    <th scope="col">Creation</th>
                    <th scope="col">Deadline</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($prospects as $prospect)
                    <tr style="vertical-align : middle;">
                        @if ($prospect->state === 4) 
                            <td><div class="fw-bold text-success"> {{ $prospect->name }} </div></td>
                        @elseif ($prospect->state === 2 || $prospect->state === 3)
                            <td><div class="fw-bold text-danger"> {{ $prospect->name }} </div></td>
                        @else
                            <td> {{ $prospect->name }} </td> 
                        @endif
                        <td>{{ countryCodeToEmojiName($prospect->country) }}</td>
                        <td>{{ $prospect->type }}</td>
                        <td>{{ $prospect->email }}</td>
                        <td>{{ $prospect->phone }}</td>
                        @if ($prospect->state === 1)
                            <td>Yes</td>
                        @elseif ($prospect->state === 2) 
                            <td>No ({{ getManagerName($prospect->actor, "first") }})</td>
                        @else 
                            <td>No</td>
                        @endif
                        <td>{{ $prospect->created_at->format('Y-m-d') }}</td>
                        <td>@if (isset($prospect->deadline)) {{ $prospect->deadline->format('Y-m-d') }} @endif</td>
                        <td><a href="{{ route('prospect.show', $prospect->id) }}" role="button" class="bi bi-eye" style="font-size: 1.8rem;"></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection