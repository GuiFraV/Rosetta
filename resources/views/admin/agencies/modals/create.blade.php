<div class="modal fade" id="createAgencyModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="createAgencyModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog">
    <div class="modal-content">      
      <div class="modal-header">
        <h5 class="modal-title" id="createAgencyModalLabel">Creation of a new agency</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formNewAgency" method="POST">
        @csrf
        <div class="modal-body" id="modal_content">
          <p>Please be cautious about the data you add because all these fields will be visible on <span class="fw-bold">EVERY</span> Intergate email!</p>  
          <label for="agencyNameNew" class="form-label">Name</label>
          <input type="text" class="form-control" name="agencyNameNew">
          <br>
          <label for="agencyAddressNew" class="form-label">Address</label>
          <input type="text" class="form-control" name="agencyAddressNew">
          <br>
          <label for="agencyPhoneNew" class="form-label">Phone number</label>
          <input type="text" class="form-control" name="agencyPhoneNew">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <input class="btn btn-primary" id="btnSubmitNewAgency" type="submit" value="Create"/>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Clear the create partner form onload
  $("#formNewAgency").each(function() { 
    this.reset(); 
  });

  // Agency creation
  let formNewAgency = $("#formNewAgency");
  formNewAgency.submit(function (e) {
    e.preventDefault(e);
    let fd = new FormData(this);

    // Front-End Validation
    let boolCheck = false;

    if(fd.get("agencyNameNew") === "") {
      toastr.warning("The agency's name is required.");
      boolCheck = true;
    }
    
    if(fd.get("agencyAddressNew") === "") {
      toastr.warning("The agency's address is required.");
      boolCheck = true;
    }

    if(fd.get("agencyAddressNew").length > 512) {
      toastr.warning("The agency's address can't exceed 512 characters.");
      boolCheck = true;
    }

    if(fd.get("agencyPhoneNew") === "") {
      toastr.warning("The agency's phone is required.");
      boolCheck = true;
    }

    if(fd.get("agencyPhoneNew").length > 512) {
      toastr.warning("The agency's phone can't exceed 512 characters.");
      boolCheck = true;
    }
  
    if(boolCheck)
      return 1;

    $.ajax({
      async: true,
      type: "POST",
      url: "{{ route('admin.agencies.store') }}",
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
          $('#createAgencyModal').modal('hide');
          $("#formNewAgency").each(function() {
            this.reset() 
          });
          toastr.success("The agency has been created!");
        }        
      },
      error: function (response) {
        console.log(response); 
      }
    });
  });
</script>