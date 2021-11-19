@extends('manager.navbar')

@section('content'))

<div class="container">
    <div id="content" class="content">
        <div class="container">
            <div class="jumbotron text-center">
                <h1 class="display-5">{{$title}}</h1>
            </div>
        </div>

        <div class="col-sm-6">
            <a href="/groups">Aller à la liste >></a>
            <form action="{{ route('manager.groups.update', ['group' => $group->id]) }}" method="POST">
                @csrf
                    <div id="appbundle_grouping">
                        <br>
                        <div class="form-group">
                            <label class="control-label required" for="appbundle_grouping_subject">Group Name</label>
                            <input type="text" id="appbundle_grouping_subject" name="groupName" required="required" maxlength="255" class="form-control" value="{{$group->groupName}}" />
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="control-label required" for="article-ckeditor">Partners</label>
                            <select class="selectpicker" multiple data-actions-box="true" data-width="fit" name="partnersId[]" data-live-search="true" data-selected-text-format="count > 2" data-size="5"    multiple>
                                @foreach ($partners as $partner)
                                    @if (in_array($partner->id , $partners_gr))
                                        <option data-subtext={{$partner->name}} value={{$partner->id}} selected>{{$partner->email}}</option>
                                    @else
                                        <option data-subtext={{$partner->name}} value={{$partner->id}}>{{$partner->email}}</option>
                                    @endif
                                    {{-- <option data-subtext={{$partner->name}} value={{$partner->id}}>{{$partner->email}}</option> --}}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <form action="{{ route('manager.groups.update', ['group' => $group->id]) }}" method="POST">
                        @method('PUT')
                        {{-- <input type="hidden" name="_method" value="PUT"> --}}
                        <button type="submit" class="btn btn-outline-primary"> Update </button>
                    </form>
            </form>
        </div>
    </div>
</div>
@endsection