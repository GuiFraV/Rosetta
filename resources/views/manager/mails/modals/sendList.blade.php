<div class="modal fade" id="newRouteListEmailModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="newRouteListEmailModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newRouteListEmailModalLabel">New route list email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formRouteListEmail" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">          
          <div class="row">          
            <div class="col">
              <label class="control-label" for="modal-input-message">Send the route list to</label>
              <select class="form-select" id="selectRouteListGroup" name="selectRouteListGroup">
                <option selected></option>
                <option value="allMyContacts">All my partners</option>
                @foreach($groups as $group)
                  <option value="{{$group->id}}">{{$group->groupName}}</option>
                @endforeach
              </select>
            </div>          
            <div class="col">
              <label class="control-label required" for="modal-input-object">Subject</label>
              <input type="text" id="emailRouteListObject" name="emailRouteListObject" required="required" maxlength="255" class="form-control"/>          
            </div>
          </div>
          <br>          
          <label class="control-label required" for="modal-input-message">Message</label>
          <textarea id="emailRouteListContent" name="emailRouteListContent" class="mce-editor" data-theme="bh" rows="10"></textarea>          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="btnSendRouteList" class="btn btn-primary">Send</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>

  $('#btnOpenModalMailRouteList').click(function() {
    $.ajax({
      async: true,
      type: "POST",
      url: "{{ route('manager.mails.getRouteList') }}",
      dataType: "JSON",
      cache: false,
      processData: false,
      contentType: false,
      success: function(data) {
        // console.log(data);
        $('#emailRouteListObject').val("Intergate Logistic " + data.typeList + " - " + data.todaysDate);
        tinymce.get('emailRouteListContent').setContent(data.list);
        $('#newRouteListEmailModal').modal('show');
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });  
  });

  let formRouteListEmail = $("#formRouteListEmail");
  formRouteListEmail.submit(function (e) {
    e.preventDefault(e);
    tinyMCE.triggerSave();
    let fd = new FormData(this);

    for(var pair of fd.entries()) {
       console.log(pair[0]+ ', '+ pair[1]);
    }

    let boolCheck = false;

    if(!fd.has('selectRouteListGroup')) {
      toastr.warning("Form error! Please select the group you wish to send the email to.")
      boolCheck = true;
    }
  
    if(!fd.has('emailRouteListObject')) {
      toastr.warning("Form error! Please select the group you wish to send the email to.")
      boolCheck = true;
    }
    
    if(boolCheck)
      return 1;

    $.ajax({
      async: true,
      type: "POST",
      // url: "mails/sendInstantMail/",
      url: "{{ route('manager.mails.sendInstantMail') }}",
      data: fd,     
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug on send
        //console.log(data);
        $('#emailDataTable').DataTable().ajax.reload();
        $('#editEmailModal').modal('hide');
        toastr.success("The email has been edited!");
      },
      error: function (request, status, error) {
        console.log("error");
      }
    });
  });

</script>