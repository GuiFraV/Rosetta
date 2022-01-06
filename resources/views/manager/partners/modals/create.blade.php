<div class="modal fade" id="createPartnerModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="createPartnerModalLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="createPartnerModalLabel">Creation of a new partner</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form id="formNewPartner" method="POST" action="{{ Route('manager.partners.store') }}">
        @csrf
        <div class="modal-body" id="modal_content">                    
          <div class="row">
            <div class="col">
              <label for="company" class="form-label">Company name</label>
              <input type="text" class="form-control" name="company">
            </div>
            <div class="col">
              <label for="contact" class="form-label">Contact name</label>
              <input type="text" class="form-control" name="contact">
            </div>
          </div>          
          <br>          
          <div class="row">
            <div class="col">
              <label for="country" class="form-label">Country</label>
              <input type="text" class="form-control" id="countryAuto">
              <input type="hidden" name="country">
            </div>                        
            <div class="col">
              <label for="phone" class="form-label">Phone number</label>
              <div class="input-group">
                <span class="input-group-text" id="callingCode"></span>
                <input type="hidden" name="callingCodeForm">
                <input type="text" class="form-control" aria-label="Phone number" aria-describedby="Phone number" name="phone">
              </div>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" name="email">
            </div>
            <div class="col">
              <label for="type" class="form-label">Type :&nbsp;&nbsp;&nbsp;</label> 
              <br>              
              <div class="form-check form-check-inline" style="margin-top: 7px;">
                <input class="form-check-input" type="radio" name="type" name="typeProspectClient" value="Client" checked>
                <label class="form-check-label" for="typeProspectClient">Client</label>
              </div>                    
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" name="typeProspectCarrier" value="Carrier">
                <label class="form-check-label" for="typeProspectCarrier">Carrier</label>
              </div>                    
            </div>         
          </div>     
          <br> 
          <div class="row">
            <div class="col">
              <label for="manager" class="form-label">Manager responsible of this partner</label>
              <input type="text" class="form-control" id="managerAuto">
              <input type="hidden" name="manager">
            </div>
            <div class="col"></div>
          </div>        
          <br>          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input class="btn btn-primary" id="btnSubmitNewPartner" type="submit" value="Create"/>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Clear the create partner form onload
  $("#formNewPartner").each(function() { 
    this.reset() 
  });
  
  // Autocompletion of the country, and phone code inputs
  $('#countryAuto').autocomplete({
    source: "{{ route('manager.partners.countryAuto') }}",
    select: function(event, ui) {
      // Set the value of the label input and the hidden input values
      $("#countryAuto").val(ui.item.label);
      $('[name="country"]').val(ui.item.value);
      $('#callingCode').html('+'+ui.item.phone_code);
      $('[name="callingCodeForm"]').val('+'+ui.item.phone_code);
      return false;
    }
  });

  // Autocompletion of the manager "owning" the partner
  $('#managerAuto').autocomplete({
    source: function(request, response) {
      $.ajax({
        url: "{{ route('manager.partners.managerAuto') }}",
        dataType: "json",
        data: {
            term : request.term,
            type : $("input:radio[name='type']:checked").val()
        },
        success: function(data) {
            response(data);
        }
      });
    },
    select: function(event, ui) {
      // Set the value of the label input and the hidden input values
      $("#managerAuto").val(ui.item.label);
      $('[name="manager"]').val(ui.item.value);
      return false;
    }
  });

  // Partner creation
  let formNewPartner = $("#formNewPartner");
  formNewPartner.submit(function (e) {
    e.preventDefault(e);
    let fd = new FormData(this);
    
    let boolCheck = true;

    if(fd.get("company") === "") {
      toastr.warning("The partner's company name is required.");
      boolCheck = false;
    }
    
    if(fd.get("contact") === "") {
      toastr.warning("The partner's contact name is required.");
      boolCheck = false;
    }

    if(fd.get("country") === "") {
      toastr.warning("The partner's origin is required.");
      boolCheck = false;
    }
    
    if(fd.get("callingCodeForm") === "") {
      toastr.warning("The partner's phone is incorrect.");
      boolCheck = false;
    }
    
    if(fd.get("phone") === "") {
      toastr.warning("The partner's phone is required.");
      boolCheck = false;
    }

    if(fd.get("email") === "") {
      toastr.warning("The partner's email is required.");
      boolCheck = false;
    }
    
    if(fd.get("type") === null) {
      toastr.warning("The partner's type is required.");
      boolCheck = false;
    }

    if(fd.get("manager") === "") {
      toastr.warning("The manager responsible of this partner is required.");
      boolCheck = false;
    }

    if(!boolCheck)
      return 1;
  
    $.ajax({
      async: true,
      type: formNewPartner.attr("method"),
      url: formNewPartner.attr("action"),
      dataType: "JSON",
      data: fd,
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug on send
        // console.log(data);
        if(data['statusCode'] === 400) {
          toastr.warning("Error while creating the new group. Try to reload the page.")
        } else if (data['statusCode'] === 200) {
          $('#partnerDataTable').DataTable().ajax.reload();
          $('#createPartnerModal').modal('hide');
          $("#formNewPartner").each(function() { 
            this.reset() 
          });
          $('#callingCode').html('');
          toastr.success("The partner has been created!");
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