@extends('layouts.layout') @section('content')

<div class="container">
    <div id="content" class="content">
        @include('inc.header')

        <div class="col-sm-6">
            <a href="/mails">Aller à la liste >></a>
            <form action="{{ route('mails.update',$mail) }}" method="POST">
                    @csrf
                    @method('PATCH')
                {{-- <form name="appbundle_mailing"  method="POST" action="{{ route('mails.update', ['mail' => $mail->id]) }}"> --}}
                    <br>
                    <div id="appbundle_mailing">
                        <div class="form-group">
                            <label class="control-label required" for="appbundle_mailing_subject">Subject</label>
                            <input type="text" id="appbundle_mailing_subject" name="object" required="required" maxlength="255" class="form-control" value="{{$mail->object}}" />
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="control-label required" for="appbundle_mailing_message">Message</label>
                            <textarea id="appbundle_mailing_message" name="message" required="required" class="tinymce form-control" data-theme="bh" rows="10"  >{{$mail->message}}</textarea>
                        </div>
                        <br>
                            
                            {{-- <input type="hidden" name="_method" value="PUT"> --}}
                            {{-- <button type="submit" class="btn btn-primary btn-block">Submit</button> --}}
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"> Update </button>
                        </div>
                {{-- </form>  --}}

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog ">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Update Email</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to update "{{$mail->object}}"
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <form action="{{ route('mails.update',$mail->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
        
                            {{-- <input type="hidden" name="_method" value="PUT"> --}}
                            {{-- <button type="submit" class="btn btn-danger btn-block">Delete</button> --}}
                            <button type="submit" class="btn btn-primary"> Update </button>
                        </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </form>    
        </div>
    </div>
</div>

@endsection