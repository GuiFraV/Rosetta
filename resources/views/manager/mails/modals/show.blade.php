<style>
  .lbl-color {
      color: #1f3d7a;
  }
</style>

<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="col">
          <h5 class="lbl-color">Email subject :</h5>
          {{-- <p id="emailShowSubject"></p> --}}
          <input id="emailShowSubject" class="form-control" type="text" disabled readonly>
        </div><br>
        <h5 class="lbl-color">Email content :</h5>
        {{-- <p id="emailShowContent"></p><br> --}}
        <textarea class="form-control mce-editor" id="emailShowContent"></textarea><br>
        <div class="row text-center">
          <div class="col">
            <h5 class="lbl-color">Automatic sending :</h5>
            <p id="emailShowAutoSend"></p>
          </div>
          
        </div>
        <div class="row text-center">
          <div class="col">          
            <h5 class="lbl-color">Created at :</h5>
            <p id="emailShowCreatedAt"></p>
          </div>
          <div class="col" id="hideShowUpdated">
            <h5 class="lbl-color">Updated at :</h5>
            <p id="emailShowUpdatedAt"></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        {{-- s<button type="button" class="btn btn-primary">Save changes</button> --}}
      </div>
    </div>
  </div>
</div>

<script>
  function openShowModal(id) {
    $.ajax({
      async: true,
      type: "GET",
      url: "mails/"+id,
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
          $('#showModalLabel').html("Mail N°"+id);
          $('#emailShowSubject').val(data['object']);
          tinymce.get('emailShowContent').setContent(data['message']);
          // Commented because it doesn't works, but the idea is to hide the toolbar with Jquery / CSS
          //$('#emailShowContent').children(".tox-editor-header").css('display','none');
          tinymce.get('emailShowContent').setMode('readonly');
          $('#emailShowAutoSend').html(data['autoSend']);
          $('#emailShowCreatedAt').html(data['created_at']);
          if(data['updated_at'] === "none") {
            $('#hideShowUpdated').hide();
          } else {
            $('#hideShowUpdated').show();
            $('#emailShowUpdatedAt').html(data['updated_at']);
          }
          $('#showModal').modal('show');
        }
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });
  }
</script>