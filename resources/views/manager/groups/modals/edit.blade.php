<div class="modal fade" id="editGroupModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="editGroupModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editGroupModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditGroup" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="editedId" id="editedId" hidden/>
        <div class="modal-body" id="modal_content">
          <div class="form-group">
            <label class="control-label required" for="groupName">Group name</label>
            <input type="text" id="groupName" name="groupName" maxlength="191" class="form-control" required="required"/>
          </div>
          <br>
          <div class="form-group">            
            <select id="multiselectEdit" name="partnersIdEdit[]" multiple="multiple" required="required"></select>            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input class="btn btn-primary" id="createBt" type="submit" value="Edit group"/>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function openModalEdit(id) {
    $.ajax({
      async: true,
      type: "GET",
      url: "groups/edit/"+id,
      dataType: "JSON",
      data: {"id": id},
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug JSON response
        // console.log(data);        
        if(data['statusCode'] === 400) {
          toastr.warning("Error while loading the group. Try to reload the page.");
          console.log(data['error']);
          return 1;
        } else if (data['statusCode'] === 200) {
          if(data['partnersOptions'] == []) {
            toastr.error("You don't have any partner on your manager account.");
            return 1;
          } else {
            $('#editedId').val(data['editedId']);
            $('#editGroupModalLabel').html("Edit group "+data['groupName']);
            $('input[name="groupName"]').val(data['groupName']);
            $('#multiselectEdit').empty();
            data['partnersOptions'].forEach(row => {            
              $('#multiselectEdit').append("<option value='"+row['value']+"'>"+row['label']+"</option>");
            });
            $('select[name="partnersIdEdit[]"]').val(data['id']);            
            
            $('#multiselectEdit').multiSelect({            
              selectableHeader: "<div class='custom-header' style='color: var(--bs-body-color);'>Available partners</div><input type='text' class='search-input src-grp' autocomplete='off' placeholder='Search'>",
              selectionHeader: "<div class='custom-header' style='color: var(--bs-body-color);'>Selected partners</div><input type='text' class='search-input src-grp' autocomplete='off' placeholder='Search'>",
              afterInit: function(ms) {
                var that = this,
                  $selectableSearch = that.$selectableUl.prev(),
                  $selectionSearch = that.$selectionUl.prev(),
                  selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                  selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                  that.qs1 = $selectableSearch.quicksearch(selectableSearchString).on('keydown', function(e) {
                    if (e.which === 40){
                      that.$selectableUl.focus();
                      return false;
                    }
                  });
                
                  that.qs2 = $selectionSearch.quicksearch(selectionSearchString).on('keydown', function(e) {
                    if (e.which == 40){
                      that.$selectionUl.focus();
                      return false;
                    }
                  });
              },
              afterSelect: function() {
                this.qs1.cache();
                this.qs2.cache();
              },
              afterDeselect: function() {
                this.qs1.cache();
                this.qs2.cache();
              }
            });
            
            $('#multiselectEdit').multiSelect('refresh');            
            $('#editGroupModal').modal('show');
          }
        }
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });  
  }

  let formEditGroup = $("#formEditGroup");
  formEditGroup.submit(function (e) {
    e.preventDefault(e);
    let fd = new FormData(this);

    let boolCheck = true;

    if(fd.get("groupName") === "") {
      toastr.warning("The group name is required.");
      boolCheck = false;
    }
    
    if(fd.get("partnersIdEdit[]") === null) {
      toastr.warning("You must select at least one partner for the group.");
      boolCheck = false;
    } 

    if(!boolCheck)
      return 1;

    $.ajax({
      async: true,
      type: "POST",
      url: "groups/update/"+$('#editedId').val(),
      data: fd,     
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug the response
        let res = JSON.parse(data);
        console.log(res);
        if(res['statusCode'] === 200) {
          $('#groupDataTable').DataTable().ajax.reload();
          $('#editGroupModal').modal('hide');
          toastr.success("The group has been edited!");
        } else if(res['statusCode'] === 400) {
          toastr.warning("There has been an error during the edition of the group. Try to reload the page.");
          console.log(res['error']);
        }  
      },
      error: function (request, status, error) {
        console.log("error");
      }
    });
  });
</script>