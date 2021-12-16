{{-- Destroy confirmation modal --}}
<div class="modal fade" id="destroyModal" tabindex="-1" aria-labelledby="destroyModalLabel" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="destroyModalLabel">Please confirm email deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <span>Are you sure you want to delete this Email?</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
        <form id="formDestroy" method="POST">  
          <input type="hidden" name="destroyedId" id="destroyedId" value="">
          <button type="submit" class="btn btn-danger"> Delete </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  let formDestroy = $('#formDestroy');
  formDestroy.submit(function(e){
    e.preventDefault(e);
    let id = e.target.elements.destroyedId.value;
    $.ajax({
      async: true,
      type: "DELETE",
      url: "mails/destroyer/"+id,
      dataType: "JSON",
      data: {"id": id},
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
          $('#destroyedId').val("");
          $('#emailDataTable').DataTable().ajax.reload();
          $('#destroyModal').modal('hide');
          toastr.warning("This email has been deleted");
      },
      error: function (request, status, error) {
          console.log(error);
      }
    });
  });
</script>