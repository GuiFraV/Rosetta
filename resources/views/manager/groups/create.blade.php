@extends('manager.navbar')

@section('content')
    
<div class="container">
  <div id="content" class="content">
    <div class="container">
      <div class="jumbotron text-center">
        <h1 class="display-5">Create new Group</h1>
      </div>
    </div>
    <div class="col-sm-6">
      <a href="{{ route('manager.groups.index') }}">Aller à la liste >></a>
      <form action="{{ route('manager.groups.savePartnerToGroup') }}" method="POST">
        @csrf
        <input id="groupIdHidden" name="groupIdHidden" type="hidden" value="secret">
          <div id="appbundle_mailing">
            <br>
            <div class="form-group">
              <label class="control-label required" for="appbundle_mailing_subject">Group Name</label>
              <input type="text" id="appbundle_mailing_subject" name="groupName" required="required" maxlength="255" class="form-control" />
              {{-- <select class="form-control" name="groupId">
                  <option selected>None selected</option>
                  @foreach ($groups as $group)
                      <option value={{$group->id}}>{{$group->groupName}}</option>
                  @endforeach
              </select> --}}
            </div>
            <br>
            <div class="form-group">
              <label class="control-label required" for="article-ckeditor">Partners</label>
              <select class="selectpicker" multiple data-actions-box="true" data-width="fit" name="partnersId[]" data-live-search="true" data-selected-text-format="count > 2" data-size="5" multiple>
                @foreach ($partners as $partner)
                  <option data-subtext={{$partner->email}} value={{$partner->id}} >{{$partner->name}} || {{$partner->company}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <br>
          <input class="btn btn-outline-primary" type="submit" value="Create" />
      </form>
    </div>
  </div>
</div>


@endsection

