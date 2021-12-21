<style>
  .ms-container {
    width: 100%;
  }
</style>
<div class="modal fade" id="createGroupModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="createGroupModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createGroupModalLabel">Creation of a new group</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formNewGroup" method="POST" action="{{ Route('manager.groups.store') }}">
        @csrf
        <div class="modal-body" id="modal_content">
          <div class="form-group">
            <label class="control-label required" for="groupName">Group name</label>
            <input type="text" id="groupName" name="groupName" maxlength="191" class="form-control" required="required"/>
          </div>
          <br>
          <div class="form-group">
            <select id="multiselect" name="partnersId[]" multiple="multiple" required="required"></select>
            <div class="btn-group" role="group" aria-label="Basic outlined example" style="width: 100%">
              <a role="button" class="btn bi bi-chevron-double-left" onclick="$('#multiselect').multiSelect('deselect_all');"></a>
              <a role="button" class="btn bi bi-chevron-double-right" onclick="$('#multiselect').multiSelect('select_all');"></a>              
            </div>  
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input class="btn btn-primary" id="createBt" type="submit" value="Create"/>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  let formNewGroup = $("#formNewGroup");
  formNewGroup.submit(function (e) {
    e.preventDefault(e);
    let fd = new FormData(this);
    
    let boolCheck = true;

    if(fd.get("groupName") === "") {
      toastr.warning("The group name is required.");
      boolCheck = false;
    }
    
    if(fd.get("partnersId[]") === null) {
      toastr.warning("You must select at least one partner for the group.");
      boolCheck = false;
    } 
      
    if(!boolCheck)
      return 1;
      
    $.ajax({
      async: true,
      type: formNewGroup.attr("method"),
      url: formNewGroup.attr("action"),
      dataType: "JSON",
      data: fd,
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug on send
        //console.log(JSON.parse(data)['data']);
        if(data['statusCode'] === 400) {
          toastr.warning("Error while creating the new group. Try to reload the page.")
        } else if (data['statusCode'] === 200) {
          $('#groupName').val("");
          $('#multiselect').multiSelect('refresh');
          $('#groupDataTable').DataTable().ajax.reload();
          $('#createGroupModal').modal('hide');
          toastr.success("The group has been created!")
        }
      },
      error: function (response) {
        $.each(response.responseJSON.errors, function(key,value) {
          toastr.warning(value);
        }); 
      }
    });
  });
</script>