<div class="modal fade" id="editAgencyModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="editAgencyModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog">
    <div class="modal-content">      
      <div class="modal-header">
        <h5 class="modal-title" id="editAgencyModalLabel">Edit an agency</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditAgency" method="POST">
        @method('PUT')
        @csrf
        <div class="modal-body" id="modal_content">
          <input type="hidden" id="editedId">
          <p>Please be cautious about the data you add because all these fields will be visible on <span class="fw-bold">EVERY</span> Intergate email!</p>  
          <label for="agencyNameEdit" class="form-label">Name</label>
          <input type="text" class="form-control" name="agencyNameEdit">
          <br>
          <label for="agencyAddressEdit" class="form-label">Address</label>
          <input type="text" class="form-control" name="agencyAddressEdit">
          <br>
          <label for="agencyPhoneEdit" class="form-label">Phone number</label>
          <input type="text" class="form-control" name="agencyPhoneEdit">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <input class="btn btn-primary" id="btnSubmitEditAgency" type="submit" value="Update"/>
        </div>
      </form>
    </div>
  </div>
</div>



<script>
  function openModalEdit(id) {
    $("#formEditAgency").each(function() { 
      this.reset(); 
    });
    $.ajax({
      async: true,
      type: "GET",
      url: "agencies/edit/"+id,
      dataType: "JSON",
      data: {"id": id},
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Uncomment to display in the console the JSON response
        console.log(data);
        if(data.error === 1) {
          toastr.warning("Error while loading the agency edition. Please reload the page and retry.");          
          return 1;
        } else if (data.error === 0) {          
          $('#editedId').val(data.agency.id);
          $('#editAgencyModalLabel').html("Edit the agency " + data.agency.name);          
          $('input[name="agencyNameEdit"]').val(data.agency.name);
          $('input[name="agencyAddressEdit"]').val(data.agency.address);
          $('input[name="agencyPhoneEdit"]').val(data.agency.phone);
          $('#editAgencyModal').modal('show');
          return 0; 
        }
      },
      error: function (request, status, error) {
        console.log(request);
        console.log(status);
        console.log(error);
        return 1;
      }
    });  
  }

  // Agency update
  let formEditAgency = $("#formEditAgency");
  formEditAgency.submit(function (e) {
    e.preventDefault(e);
    let id = $('#editedId').val();
    let fd = new FormData(this);
    fd.append('editedId', id);
    // Front-End Validation
    let boolCheck = false;

    if(fd.get("agencyNameEdit") === "") {
      toastr.warning("The agency's name is required.");
      boolCheck = true;
    }
    
    if(fd.get("agencyAddressEdit") === "") {
      toastr.warning("The agency's address is required.");
      boolCheck = true;
    }

    if(fd.get("agencyAddressEdit").length > 512) {
      toastr.warning("The agency's address can't exceed 512 characters.");
      boolCheck = true;
    }

    if(fd.get("agencyPhoneEdit") === "") {
      toastr.warning("The agency's phone is required.");
      boolCheck = true;
    }

    if(fd.get("agencyPhoneEdit").length > 512) {
      toastr.warning("The agency's phone can't exceed 512 characters.");
      boolCheck = true;
    }
  
    if(boolCheck)
      return 1;

    $.ajax({
      async: true,
      type: "POST",
      url: "agencies/update/"+id,
      dataType: "JSON",
      data: fd,
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        if(data.error == 1) {
          toastr.warning(data.message);
        } else if (data.error == 0) {
          $('#agencyDataTable').DataTable().ajax.reload();
          $('#editAgencyModal').modal('hide');
          $("#formEditAgency").each(function() {
            this.reset() 
          });
          toastr.success("The agency has been updated!");
        }        
      },
      error: function (response) {
        console.log(response); 
      }
    });
  });
</script>