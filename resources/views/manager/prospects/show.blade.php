@extends('manager.navbar')

@section('content')
    <style>
        .lbl-color {
            color: #1f3d7a;
        }
    </style>
    <div class="container col-6">
        <div class="float-end">
            <div style="display: flex; font-size: 2rem;">
                
                @if (Auth::user()->role_id === 1 || getManagerType() === ($prospect->type == "Client" ? "TM" : "LM") && $prospect->state != 4)
                    
                    <!-- Archive a booked prospect -->
                    @if (getManagerId() == $prospect->actor)
                        <a href="{{ route('manager.result', $prospect->id) }}" role="button" class="bi bi-archive" style="margin-right: 5px; "></a>
                    @endif

                    <!-- Book an available prospect -->
                    @if (($prospect->state === 1 && (!isset($prospect->unavailable_until) || $prospect->unavailable_until < date("Y-m-d H:i:s"))) || Auth::user()->role_id === 1)
                        @if(App\Models\Prospect::all()->where('actor', '=', getManagerId())->count() < 5 || Auth::user()->role_id === 1)
                            <a href="{{ route('manager.formBooking', $prospect->id) }}" role="button" class="bi bi-bookmark" style="margin-right: 5px; "></a>
                        @endif
                    @endif

                    <!-- Edit a prospect -->
                    @if (getManagerId() === $prospect->creator || Auth::user()->role_id === 1)
                        <a href="{{ route('manager.prospect.edit', $prospect->id) }}" role="button" class="bi bi-pencil" style="margin-right: 5px; "></a>
                    @endif

                    <!-- Delete prospect -->
                    @if (Auth::user()->role_id === 1)
                        <form id="destroy{{ $prospect->id }}" action="{{ route('manager.prospect.destroy', $prospect->id) }}" method="POST">
                            @csrf
                            @method('DELETE')                      
                            <a role="button" class="bi bi-trash" style="margin-right: 5px;" onclick="event.preventDefault(); this.closest('form').submit();"></a>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        @if(!empty($created))
            <script>toastr.success('{{ $created }}');</script>
        @elseif(!empty($edited))
            <script>toastr.success('{{ $edited }}');</script>
        @elseif(!empty($booked))
            <script>toastr.success('{{ $booked }}');</script>
        @elseif(!empty($offer_created))
            <script>toastr.success('{{ $offer_created }}');</script>
        @elseif(!empty($offer_edited))
            <script>toastr.success('{{ $offer_edited }}');</script>
        @elseif(!empty($offer_deleted))
            <script>toastr.warning('{{ $offer_deleted }}');</script>
        @elseif(!empty($comment_created))
            <script>toastr.success('{{ $comment_created }}');</script>
        @elseif(!empty($comment_edited))
            <script>toastr.warning('{{ $comment_edited }}');</script>
        @endif

        <h2>Prospect details @if ($prospect->state === 4 && !isset($prospect->deadline)) <div class="text-success">(Validated)</div> @endif</h2>
        <hr style="border-top-width: 2px;border-top-style: solid; color: black; opacity: 75%">
        <div class="row">
            <div class="col">
                <h5 class="lbl-color">Name</h5>
                <p>{{ $prospect->name }}</p>
            </div>
            <div class="col">
                <h5 class="lbl-color">Origin</h5>
                <p>{{ countryCodeToEmojiName($prospect->country) }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h5 class="lbl-color">Email</h5>
                <p>{{ $prospect->email }}</p>
            </div>
            <div class="col">
                <h5 class="lbl-color">Phone</h5>
                <p>{{ $prospect->phone }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h5 class="lbl-color">Type</h5>
                <p>{{ $prospect->type }}</p>
            </div>
            <div class="col">    
                <h5 class="lbl-color">Current State</h5>
                <p>{{ (isset($prospect->unavailable_until) && $prospect->unavailable_until > date("Y-m-d H:i:s")) ? "On stand-by" : getStateToHuman($prospect->state) }}</p>
            </div>
        </div>
        @if ($prospect->state == 2)
            <div class="row">
                <div class="col">
                    <p>Booked by <b>{{ getManagerName($prospect->actor, "all") }}</b>, until <b>{{ $prospect->deadline->format('Y-m-d') }}</b>.</p>
                </div>
            </div>
        @endif
        @if (isset($prospect->unavailable_until) && $prospect->unavailable_until > date("Y-m-d H:i:s"))
            <div class="row">
                <div class="col">
                    <p>This prospect is currently <b>on stand-by</b> until <b>{{ $prospect->unavailable_until->format('Y-m-d') }}</b>.</p>
                </div>
            </div>
        @endif
        @if ($prospect->state == 4)
            <div class="row">
                <div class="col">
                    <p>This prospect has been validated by <b>{{ getManagerName($prospect->actor, "all") }}</b> with the load number <b>{{ $prospect->loadNumber }}</b>.</p>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col">
                <h5 class="lbl-color">Created</h5>
                <p>{{ $prospect->created_at->format('Y-m-d h:m') }}</p>
            </div>
            @if ($prospect->updated_at != $prospect->created_at)
                <div class="col">    
                    <h5 class="lbl-color">Last update</h5>
                    <p>{{ $prospect->updated_at->format('Y-m-d h:m') }}</p>        
                </div>
            @endif
        </div>
    </div>
        @if(!$trackings->isEmpty())
            <div class="container">
                <br>
                <h2>History</h2>
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
                                <td>{{ getManagerName($tracking->actor, "all") }}</td>
                                <td>{{ $tracking->created_at->format('Y-m-d') }}</td>
                                <td>{{ $tracking->comment }}</td>
                                @if (getManagerId() === $tracking->actor)
                                    <td><a href="{{ route('manager.tracking.edit', $tracking->id) }}" role="button" class="bi bi-pencil" style="font-size: 1.3rem;"></a></td>
                                @else
                                    <td></td> 
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="container col-6">
                <br><p>There is no exchange history for this prospect!</p>
            </div>
        @endif
    
        @if(!$offers->isEmpty())
            <div class="container">
                <br>
                <a href="{{ route('manager.offer.create', ['prospect' => $prospect->id]) }}" role="button" class="float-end bi bi-plus-lg text-success" style="margin-right: 5px; font-size: 2rem;"></a>
                <h2>Offers</h2>                
                <table class="table table-hover" style="font-size: 95%">
                    <thead>
                        <tr>
                            <th scope="col">Actor</th>
                            <th scope="col">From</th>
                            <th scope="col">To</th>
                            <th scope="col">Offer</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Date</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offers as $offer)
                            <tr style="vertical-align : middle;">
                                <td>{{ getManagerName($offer->actor, "all") }}</td>
                                <td>{{ $offer->cityFrom }}</td>
                                <td>{{ $offer->cityTo }}</td>
                                <td>{{ $offer->offer ."€" }} </td>
                                <td>{{ $offer->comment }}</td>
                                <td>{{ $offer->created_at->format('Y-m-d') }}</td>
                                @if (getManagerId() === $offer->actor)
                                    <td><a href="{{ route('manager.offer.edit', $offer->id) }}" role="button" class="bi bi-pencil" style="font-size: 1.3rem;"></a></td>
                                @else
                                    <td></td> 
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="container col-6">
                <br>
                <a href="{{ route('manager.offer.create', ['prospect' => $prospect->id]) }}" role="button" class="float-end btn btn-success" style="margin-right: 5px;">Add a new offer</a>
                <p>There is no offer for this prospect!</p>
            </div>
        @endif

        @if(!$comments->isEmpty())
            <div class="container">
                <br>
                <a href="{{ route('manager.comment.create', ['prospect' => $prospect->id]) }}" role="button" class="float-end bi bi-plus-lg text-success" style="margin-right: 5px; font-size: 2rem;"></a>
                <h2>Comments</h2><br>                
                @foreach($comments as $comment)
                <div class="list-group">
                    <div class="list-group-item list-group-item-action" aria-current="true">
                        <div class="d-flex w-100 justify-content-between">
                            <small>By {{ getManagerName($comment->author, "all") }}, the {{ $comment->created_at->format('Y-m-d \a\t H:i') }}</small>
                            @if (getManagerId() === $comment->author || Auth::user()->role_id === 1)
                                <small><a href="{{ route('manager.comment.edit', $comment->id) }}" role="button" class="float-end bi bi-pencil"></a></small>
                            @endif
                        </div>
                        <p class="mb-1" style="margin-top: 10px;">{{ $comment->comment }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="container col-6">
                <br>
                <a href="{{ route('manager.comment.create', ['prospect' => $prospect->id]) }}" role="button" class="float-end btn btn-success" style="margin-right: 5px;">Add a new comment</a>
                <p>There is no comment on this prospect!</p>
            </div>
        @endif
@endsection