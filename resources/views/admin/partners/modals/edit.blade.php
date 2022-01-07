<div class="modal fade" id="editPartnerModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="editPartnerModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editPartnerModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formEditPartner" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="editedId" id="editedId" hidden/>
        
        <div class="modal-body" id="modal_content">                    
          <div class="row">
            <div class="col">
              <label for="partnerEditCompany" class="form-label">Company name</label>
              <input type="text" class="form-control" name="partnerEditCompany">
            </div>
            <div class="col">
              <label for="partnerEditContact" class="form-label">Contact name</label>
              <input type="text" class="form-control" name="partnerEditContact">
            </div>
          </div>          
          <br>          
          <div class="row">
            <div class="col">
              <label for="partnerEditCountryAuto" class="form-label">Country</label>
              <input type="text" class="form-control" id="partnerEditCountryAuto">
              <input type="hidden" name="partnerEditCountry">
            </div>                        
            <div class="col">
              <label for="partnerEditPhone" class="form-label">Phone number</label>
              <div class="input-group">
                <span class="input-group-text" id="partnerEditCallingCode"></span>
                <input type="text" class="form-control" aria-label="Phone number" aria-describedby="Phone number" name="partnerEditPhone">
                <input type="hidden" name="partnerEditCallingCodeForm">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col">
              <label for="partnerEditEmail" class="form-label">Email</label>
              <input type="email" class="form-control" name="partnerEditEmail">
            </div>
            <div class="col">
              <label for="partnerEditType" class="form-label">Type :&nbsp;&nbsp;&nbsp;</label> 
              <br>              
              <div class="form-check form-check-inline" style="margin-top: 7px;">
                <input class="form-check-input" type="radio" name="partnerEditType" name="typePartnerClient" value="Client" checked>
                <label class="form-check-label" for="typePartnerClient">Client</label>
              </div>                    
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="partnerEditType" name="typePartnerCarrier" value="Carrier">
                <label class="form-check-label" for="typePartnerCarrier">Carrier</label>
              </div>                    
            </div>         
          </div>     
          <br> 
          <div class="row">
            <div class="col">
              <label for="partnerEditManager" class="form-label">Manager responsible of this partner</label>
              <input type="text" class="form-control" id="partnerEditManagerAuto">
              <input type="hidden" name="partnerEditManager">
            </div>
            <div class="col"></div>
          </div>        
          <br>          
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <input class="btn btn-primary" id="createBt" type="submit" value="Edit partner"/>
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
      url: "partners/edit/"+id,
      dataType: "JSON",
      data: {"id": id},
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Uncomment to display in the console the JSON response
        // console.log(data);    
        if(data['statusCode'] === 400) {
          toastr.warning("Error while loading the partner. Try to reload the page.");          
          return 1;
        } else if (data['statusCode'] === 200) {          
          $('#editPartnerModalLabel').html("Edit the partner " + data['company']);
          $('#editedId').val(data['editedId']);          
          $('input[name="partnerEditCompany"]').val(data['company']);
          $('input[name="partnerEditContact"]').val(data['contact']);
          $('#partnerEditCountryAuto').val(data['originLabel']);
          $('input[name="partnerEditCountry"]').val(data['originValue']);
          $('#partnerEditCallingCode').html(data['phone_code']);
          $('input[name="partnerEditPhone"]').val(data['phone']);
          $('input[name="partnerEditCallingCodeForm"]').val(data['phone_code']);
          $('input[name="partnerEditEmail"]').val(data['email']);
          if(data['type'] === "Client") {            
            $("input[name=partnerEditType][value='Client']").prop("checked",true);          
          } else if (data['type'] === "Carrier") {            
            $("input[name=partnerEditType][value='Carrier']").prop("checked",true);
          }          
          $('#partnerEditManagerAuto').val(data['managerLabel']);
          $('input[name="partnerEditManager"]').val(data['managerValue']);
          $('#editPartnerModal').modal('show');
          return 0;          
        }
      },
      error: function (request, status, error) {
        console.log(error);
        return 1;
      }
    });  
  }
  
  // Autocompletion of the country, and phone code inputs
  $('#partnerEditCountryAuto').autocomplete({
    source: "{{ route('admin.partners.countryAuto') }}",
    select: function(event, ui) {
      // Set the value of the label input and the hidden input values
      $("#partnerEditCountryAuto").val(ui.item.label);
      $('[name="partnerEditCountry"]').val(ui.item.value);
      $('#partnerEditCallingCode').html('+'+ui.item.phone_code);
      $('[name="partnerEditCallingCodeForm"]').val('+'+ui.item.phone_code);
      return false;
    }
  });

  // Autocompletion of the manager "owning" the partner
  $('#partnerEditManagerAuto').autocomplete({
    source: function(request, response) {
      $.ajax({
        url: "{{ route('admin.partners.managerAuto') }}",
        dataType: "json",
        data: {
            term : request.term,
            type : $("input:radio[name='partnerEditType']:checked").val()
        },
        success: function(data) {
            response(data);
        }
      });
    },
    select: function(event, ui) {
      // Set the value of the label input and the hidden input values
      $("#partnerEditManagerAuto").val(ui.item.label);
      $('[name="partnerEditManager"]').val(ui.item.value);
      return false;
    }
  });

  // Partner edition
  let formEditPartner = $("#formEditPartner");
  formEditPartner.submit(function (e) {
    e.preventDefault(e);
    let fd = new FormData(this);

    // Front validation
    let boolCheck = true;

    if(fd.get("partnerEditCompany") === "") {
      toastr.warning("The partner's company name is required.");
      boolCheck = false;
    }
    
    if(fd.get("partnerEditContact") === "") {
      toastr.warning("The partner's contact name is required.");
      boolCheck = false;
    }

    if(fd.get("partnerEditCountry") === "") {
      toastr.warning("The partner's origin is required.");
      boolCheck = false;
    }
    
    if(fd.get("partnerEditCallingCodeForm") === "") {
      toastr.warning("The partner's phone is incorrect.");
      boolCheck = false;
    }
    
    if(fd.get("partnerEditPhone") === "") {
      toastr.warning("The partner's phone is required.");
      boolCheck = false;
    }

    if(fd.get("partnerEditEmail") === "") {
      toastr.warning("The partner's email is required.");
      boolCheck = false;
    }
    
    if(fd.get("partnerEditType") === null) {
      toastr.warning("The partner's type is required.");
      boolCheck = false;
    }

    if(fd.get("partnerEditManager") === "") {
      toastr.warning("The manager responsible of this partner is required.");
      boolCheck = false;
    } 

    if(!boolCheck)
      return 1;

    $.ajax({
      async: true,
      type: "POST",
      url: "partners/update/"+$('#editedId').val(),
      data: fd,     
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug the response
        data = JSON.parse(data);
        // console.log(data);        
        if(data['statusCode'] === 200) {
          $('#partnerDataTable').DataTable().ajax.reload();
          $('#editPartnerModal').modal('hide');
          toastr.success("The partner has been edited!");
        } else if(data['statusCode'] === 400 && data['error']) {
          toastr.warning("There has been an error during the edition of the partner. Please retry to send your edition.");          
        } else if(data['statusCode'] === 400) {
          toastr.warning("The specified partner has not been found. Please try to reload the page.");
          console.log(data['error']);
        }
      },
      error: function(request, status, error) {
        console.log(error);
      }
    });
  });

</script>