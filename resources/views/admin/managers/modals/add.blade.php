<div class="modal fade" id="addmanagermodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formNewManager" action="{{ route('admin.managers.store') }}" method="post">
      @csrf
      @method('POST')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add a manager</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
          
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleFormControlInput1">First name</label>
            <input type="text" class="form-control" id="first_name_add_manager" name="first_name_add_manager">
          </div>
          <div class="form-group">
            <label for="exampleFormControlInput1">Last name</label>
            <input type="text" class="form-control" id="last_name_add_manager" name="last_name_add_manager">
          </div>
          <div class="form-group">
            <label for="exampleFormControlInput1">Email</label>
            <input type="email" class="form-control" id="email_add_manager" name="email_add_manager">
          </div>
          <div class="form-group">
            <label for="exampleFormControlInput1">Password</label>
            <input type="text" class="form-control" id="password_add_manager" name="password_add_manager">
          </div>
          <div class="form-group">
            <label for="exampleFormControlInput1">Signature</label>
            <input type="text" class="form-control" id="signature_add_manager" name="signature_add_manager">
          </div>
          <div class="form-group">
            <label for="exampleFormControlInput1">Agency</label>
            <select  class="form-control" id="agency_add_manager" name="agency_add_manager">
              @foreach ($agencies as $item)
                <option value="{{$item->id}}">{{$item->agency_name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="exampleFormControlInput1">Type of Manager :</label>
            <input class="form-check-input" type="radio" name="manager_add_radios" id="LM_add_manager" value="LM">
            <label class="form-check-label" for="LM_add_manager">Logistic Manager</label>
            <input class="form-check-input" type="radio" name="manager_add_radios" id="TM_add_manager" value="TM">
            <label class="form-check-label" for="TM_add_manager">Transport Manager</label>
          </div>
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
  
  let formNewManager = $("#formNewManager");  
  formNewManager.submit(function (e) {
    
    e.preventDefault();

    let fd = new FormData(this);

    for(var pair of fd.entries()) {
       console.log(pair[0]+ ', '+ pair[1]);
    }

    return 0;

    let boolCheck = true;

    if(fd.get("first_name_add_manager") === "") {
      toastr.warning("Form Error! The first name is required.");
      boolCheck = false;
    }

    if(fd.get("first_name_add_manager").length > 191) {
      toastr.warning("Form Error! The first name can't exceed 190 characters.");
      boolCheck = false;
    }
    
    if(fd.get("last_name_add_manager") === "") {
      toastr.warning("Form Error! The last name is required.");
      boolCheck = false;
    }

    if(fd.get("last_name_add_manager").length > 191) {
      toastr.warning("Form Error! The last name can't exceed 190 characters.");
      boolCheck = false;
    }
    
    if(fd.get("email_add_manager") === "") {
      toastr.warning("Form Error! The email is required.");
      boolCheck = false;
    }
    
    if(fd.get("email_add_manager").length > 191) {
      toastr.warning("Form Error! The email can't exceed 190 characters.");
      boolCheck = false;
    }

    if(fd.get("password_add_manager") === "") {
      toastr.warning("Form Error! The password is required.");
      boolCheck = false;
    }
    
    if(fd.get("password_add_manager").length > 191) {
      toastr.warning("Form Error! The password can't exceed 190 characters.");
      boolCheck = false;
    }

    if(fd.get("signature_add_manager") === "") {
      toastr.warning("Form Error! The signature is required.");
      boolCheck = false;
    }

    if(fd.get("signature_add_manager").length > 191) {
      toastr.warning("Form Error! The signature can't exceed 190 characters.");
      boolCheck = false;
    }
    
    if(fd.get("agency_add_manager") < 1 || fd.get("agency_add_manager") > 7) {
      toastr.warning("Form Error! There is an error with the agency.");
      boolCheck = false;
    }
    
    if(!fd.has("manager_add_radios")) {
      toastr.warning("Form Error! The manager type is required.");
      boolCheck = false;
    } else if(fd.get("manager_add_radios") != "TM" && fd.get("manager_add_radios") != "LM") {
      toastr.warning("Form Error! The manager type is incorrect, please try to reload the page.");
      boolCheck = false;
    } 
      
    if(!boolCheck)
      return 1;
      
    formNewManager.unbind().submit();
  });

</script>