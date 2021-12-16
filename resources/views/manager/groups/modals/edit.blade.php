<div class="modal fade" id="editEmailModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="editEmailModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editEmailModalLabel">Edit Email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditMail" method="POST" enctype="multipart/form-data"">
        @csrf
        <input type="text" name="editedId" id="editedId" hidden/>
        <div class="modal-body">          
          <div class="form-group">
              <label class="control-label required" for="modal-input-object">Subject</label>
              <input type="text" id="emailEditObject" name="emailEditObject" required="required" maxlength="255" class="form-control"/>
          </div>
          <br>
          <div class="form-group">
            <label class="control-label required" for="modal-input-message">Message</label>
            <textarea id="emailEditContent" name="emailEditContent" class="mce-editor" data-theme="bh" rows="10"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btn_update" class="btn btn-warning">Edit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function openModalEditMail(id) {
    $.ajax({
      async: true,
      type: "GET",
      url: "mails/edit/"+id,
      dataType: "JSON",
      data: {"id": id},
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug JSON response
        console.log(data);
        if(data['statusCode'] === 400) {
          toastr.warning("Specified email has not been found. Try to reload the page.")
        } else if (data['statusCode'] === 200) {
          $('#editedId').val(data['id']);
          $('#emailEditObject').val(data['object']);
          tinymce.get('emailEditContent').setContent(data['message']);
          $('#emailEditAutoSend').html(data['autoSend']);
          $('#editEmailModal').modal('show');
        }
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });  
  }

  let formEditMail = $("#formEditMail");
  formEditMail.submit(function (e) {
    e.preventDefault(e);
    tinyMCE.triggerSave();
    let fd = new FormData(this);
    $.ajax({
      async: true,
      type: "POST",
      url: "mails/update/"+$('#editedId').val(),
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