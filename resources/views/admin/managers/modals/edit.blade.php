<div class="modal fade" id="editmanagermodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="formEditManager" action="{{ route('admin.managers.update','1') }}" method="post">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit a manager</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="id_manager" name="id_manager"/>
          <div class="row">
            <div class="col">
              <label for="first_name_edit_manager">First name</label>
              <input type="text" class="form-control" id="first_name_edit_manager" name="first_name_edit_manager">
            </div>
            <div class="col">
              <label for="last_name_edit_manager">Last name</label>
              <input type="text" class="form-control" id="last_name_edit_manager" name="last_name_edit_manager">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col">
              <label for="email_edit_manager">Email</label>
              <input type="email" class="form-control" id="email_edit_manager" name="email_edit_manager">
            </div>
            <div class="col">
              <label for="phone_edit_manager">Phone number</label>
              <input type="text" class="form-control" id="phone_edit_manager" name="phone_edit_manager">
            </div>
          </div>
          <br>
          <div class="row">            
            <div class="col">  
              <label for="skype_edit_manager">Skype</label>
              <input type="text" class="form-control" id="skype_edit_manager" name="skype_edit_manager">
            </div>
            <div class="col">
              <label for="agency_edit_manager">Agency</label>
              <select  class="form-control" id="agency_edit_manager" name="agency_edit_manager">
                @foreach ($agencies as $item)
                  <option value="{{$item->id}}">{{$item->agency_name}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <br>
          <label for="manager_edit_radios">Type of Manager :</label>&nbsp&nbsp
          <input class="form-check-input" type="radio" name="manager_edit_radios" id="LM_edit_manager" value="LM">
          <label class="form-check-label" for="LM_edit_manager">Logistic Manager</label>&nbsp&nbsp
          <input class="form-check-input" type="radio" name="manager_edit_radios" id="TM_edit_manager" value="TM">
          <label class="form-check-label" for="TM_edit_manager">Transport Manager</label>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  
  let formEditManager = $("#formEditManager");  
  formEditManager.submit(function(e) {
    e.preventDefault();
    let fd = new FormData(this);
    let boolCheck = true;
    
    // Front-End validation of the form entries
    if(!fd.has("id_manager")) {
      toastr.warning("Form Error! Please reload the page and retry.");
      boolCheck = false;
    }

    if(fd.get("first_name_edit_manager") === "") {
      toastr.warning("Form Error! The first name is required.");
      boolCheck = false;
    }

    if(fd.get("first_name_edit_manager").length > 191) {
      toastr.warning("Form Error! The first name can't exceed 190 characters.");
      boolCheck = false;
    }
    
    if(fd.get("last_name_edit_manager") === "") {
      toastr.warning("Form Error! The last name is required.");
      boolCheck = false;
    }

    if(fd.get("last_name_edit_manager").length > 191) {
      toastr.warning("Form Error! The last name can't exceed 190 characters.");
      boolCheck = false;
    }
    
    if(fd.get("email_edit_manager") === "") {
      toastr.warning("Form Error! The email is required.");
      boolCheck = false;
    }
    
    if(fd.get("email_edit_manager").length > 191) {
      toastr.warning("Form Error! The email can't exceed 190 characters.");
      boolCheck = false;
    }

    if(fd.get("phone_edit_manager") === "") {
      toastr.warning("Form Error! The phone is required.");
      boolCheck = false;
    }
    
    if(fd.get("phone_edit_manager").length > 191) {
      toastr.warning("Form Error! The phone can't exceed 190 characters.");
      boolCheck = false;
    }

    if(fd.get("skype_edit_manager") === "") {
      toastr.warning("Form Error! The skype is required.");
      boolCheck = false;
    }
    
    if(fd.get("skype_edit_manager").length > 191) {
      toastr.warning("Form Error! The skype can't exceed 190 characters.");
      boolCheck = false;
    }

    if(fd.get("manager_edit_radios") < 1 || fd.get("manager_edit_radios") > 7) {
      toastr.warning("Form Error! There is an error with the agency.");
      boolCheck = false;
    } else if(fd.get("manager_edit_radios") != "TM" && fd.get("manager_edit_radios") != "LM") {
      toastr.warning("Form Error! The manager type is incorrect, please try to reload the page.");
      boolCheck = false;
    } 
      
    if(!boolCheck)
      return 1;
      
    formEditManager.unbind().submit();
  });

</script>