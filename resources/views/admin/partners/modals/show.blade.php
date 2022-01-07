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
        
        <div class="row text-center">
          <div class="col">
            <h5 class="lbl-color">Company name :</h5>
            <p id="partnerShowCompany"></p>
          </div>
          <div class="col">
            <h5 class="lbl-color">Contact name :</h5>
            <p id="partnerShowContact"></p>
          </div>
        </div>

        <div class="row text-center">
          <div class="col">
            <h5 class="lbl-color">Origin :</h5>
            <p id="partnerShowOrigin"></p>
          </div>
          <div class="col">
            <h5 class="lbl-color">Type :</h5>
            <p id="partnerShowType"></p>
          </div>                    
        </div>

        <div class="row text-center">
          <div class="col">
            <h5 class="lbl-color">Phone number :</h5>
            <p id="partnerShowPhone"></p>
          </div>
          <div class="col">            
            <h5 class="lbl-color">Email address :</h5>
            <p id="partnerShowEmail"></p>
          </div>          
        </div>

        <div class="row text-center">
          <div class="col">
            <h5 class="lbl-color">Manager :</h5>
            <p id="partnerShowManager"></p>
          </div>
          <div class="col">          
            <h5 class="lbl-color">Created at :</h5>
            <p id="partnerShowCreatedAt"></p>
          </div>
          <div class="col" id="hideShowUpdated">
            <h5 class="lbl-color">Updated at :</h5>
            <p id="partnerShowUpdatedAt"></p>
          </div>
        </div>        
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  function openShowModal(id) {
    $.ajax({
      async: true,
      type: "GET",
      url: "partners/"+id,
      dataType: "JSON",
      data: {"id": id},
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Uncomment to display in the console the JSON response
        // console.log(data);        
        if(data['statusCode'] === 200) {  
          $('#showModalLabel').html(data["company"]+"'s details");        
          $('#partnerShowCompany').html(data["company"]);
          $('#partnerShowContact').html(data["contact"]);
          $('#partnerShowOrigin').html(data["origin"]);
          $('#partnerShowPhone').html(data["phone"]);
          $('#partnerShowEmail').html(data["email"]);
          $('#partnerShowType').html(data["type"]);
          $('#partnerShowManager').html(data["manager"]);
          $('#partnerShowCreatedAt').html(data["created_at"]);         
          if(data["updated_at"] === "none") {
            $('#hideShowUpdated').hide();
          } else {
            $('#partnerShowUpdatedAt').html(data["updated_at"]);
            $('#hideShowUpdated').show();                
          }
          $('#showModal').modal('show');
          return 0;              
        } else {
          toastr.warning("The specified partner has not been found. Try to reload the page.");
          return 1;
        }
      },
      error: function (request, status, error) {
        console.log(error);
        return 1;
      }      
    });
  }
</script>