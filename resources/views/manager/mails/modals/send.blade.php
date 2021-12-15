<div class="modal fade" id="sendModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="sendModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sendModalLabel">Send Email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form name="appbundle_mailing" id="sendForm" method="POST" action="{{ route('testSendMail')}}">
        @csrf
        <div class="modal-body">
          <input type="text" name="mailId1" id="mailId1" hidden>
          <div class="container">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="sendType" id="sendType2" onclick="myFunction()" checked>
              <label class="form-check-label" for="sendType2">Manual Send</label>
            </div>
            {{-- <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="sendType" id="sendType1" onclick="myFunction()">
              <label class="form-check-label" for="sendType1">Auto Send</label>
            </div> --}}
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="sendType" id="sendType3" onclick="myFunction()">
              <label class="form-check-label" for="sendType3">Send At</label>
            </div>
          </div>
          <div class="container">
            <label for="group">Group</label>
              <div class="form-group">
            <select class="form-select" id="group_id" name="group_id" aria-label="Default select example">
                    <option selected></option>
                  {{-- COMMENTED BECAUSE THE ROUTE WASNT WORKING WITH GROUP VAR
                    @foreach($groups as $group)
                      <option value="{{$group->id}}">{{$group->groupName}}</option>
                    @endforeach
                  --}}
            </select>
              </div> 
            </div>
          {{-- <div class="container" id="divTime" style="display:none;">
            <br>
            <label for="time">Time</label>
            <select class="form-select" id="time" aria-label="Default select example">
              <option selected></option>
              <option value="time1">08:45</option>
              <option value="time2">10:00</option>
              <option value="time3">11:30</option>
              <option value="time4">14:30</option>
              <option value="time5">16:00</option>
            </select>
          </div> --}}
          <div class="container" id="divDate" style="display:none;">
            <br>
            <input id="sendDate" name="sendDate" type="datetime-local" value="{{Carbon\Carbon::now()->format('Y-m-d\TH:i:s')}}">
            {{-- <input id="sss" name="ss" type="datetime-local" value="{{Carbon\Carbon::now()->format('Y-m-d\TH:i:s.u')}}"> --}}
          </div>
        </div>
        <div class="modal-footer">
          
          {{-- <form action="{{ route('mails.update', ['mail' => $mail->id]) }}" method="POST">
            <a class="btn btn-primary" href="{{ route('mails.edit',$mail->id) }}">Edit</a>
                </form> --}}
          <a class="btn btn-secondary" href="/mails">Close</a>
          {{-- COMMENTED BECAUSE CRASH WITH IT --}}
          {{-- <button class="btn btn-primary" id="sendMailUpdate" data-bs-target="#updateModal" data-bs-toggle="modal" onclick="editLast({{$lastMail}})">Update</button> --}}
          <button class="btn btn-success" type="submit" id="sendSubmit" data-bs-dismiss="modal" href="{{ url('testSendMail/1') }}">Send</button>

        </div>
      </form>
    </div>
  </div>
</div>