@extends('layout.app')

@section('tracking')
    <h2>History</h2>
    @if(isset($trackings))
        <table class="table table-hover" style="font-size: 95%">
            <thead>
                <tr>
                    <th scope="col">Actor</th>
                    <th scope="col">Date</th>
                    <th scope="col">Comment</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($trackings as $tracking)
                    <tr style="vertical-align : middle;">
                        <td>{{ $tracking->actor }}</td>
                        <td>{{ $tracking->created_at }}</td>
                        <td>{{ $tracking->comment }}</td>
                        <td><a href="{{ route('tracking.edit', $tracking->id) }}" role="button" class="bi bi-pencil" style="font-size: 1.8rem;"></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection