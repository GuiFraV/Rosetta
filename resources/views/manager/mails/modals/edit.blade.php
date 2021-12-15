<div class="modal fade" id="editEmailModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="editEmailModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="editEmailModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditMail" method="POST">
        <input type="text" name="id1" id="id" hidden>
        <div class="modal-body">
          <div id="appbundle_mailing">
              <br>
              <div class="form-group">
                  <label class="control-label required" for="modal-input-object">Subject</label>
                  <input type="text" id="modal-input-object" name="object" required="required" maxlength="255"
                      class="form-control" />
              </div>
              <br>
              <div class="form-group">
                <label class="control-label required" for="modal-input-message">Message</label>
                <textarea id="emailEditContent" name="emailEditContent" class="mce-editor" data-theme="bh" rows="10"></textarea>
              </div>
          </div>
          <br>
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
        /// Debug on send
        //console.log(data);
        if(data['statusCode'] === 400) {
          toastr.warning("Specified email has not been found. Try to reload the page.")
        } else if (data['statusCode'] === 200){
          $('#editMailModalLabel').html("Edit Email N°"+id);
          tinymce.get('emailEditContent').setContent(data['message']);
          $('#emailEditAutoSend').html(data['autoSend']);
          $('#editEmailModal').modal('show');
        }
        console.log("success ajax");
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });  
  }
</script>