@extends('manager.navbar')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- Compiled and minified MultiSelect JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js" integrity="sha512-FHZVRMUW9FsXobt+ONiix6Z0tIkxvQfxtCSirkKc5Sb4TKHmqq1dZa8DphF0XqKb3ldLu/wgMa8mT6uXiLlRlw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
<!-- Compiled and minified MultiSelect CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" integrity="sha512-mR/b5Y7FRsKqrYZou7uysnOdCIJib/7r5QeJMFvLNHNhtye3xJp1TdJVPLtetkukFn227nKpXD9OjUc09lx97Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />   
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

