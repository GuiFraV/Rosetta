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
        <table class="table table-striped table-hover">
          <h5 class="lbl-color">Partners in this group :</h5>
          <thead>
            <tr>
              <th>Name</th>
              <th>Origin</th>
              <th>Phone</th>
              <th>Email</th>
              {{-- <th>Go to partner details</th> --}}
            </tr>
          </thead>
          <tbody id="groupShowTable"></tbody>
        </table>
        <div class="row text-center">
          <div class="col">
            <h5 class="lbl-color">Creator :</h5>
            <p id="groupShowCreator"></p>
          </div>
        </div>
        <div class="row text-center">
          <div class="col">          
            <h5 class="lbl-color">Created at :</h5>
            <p id="groupShowCreatedAt"></p>
          </div>
          <div class="col" id="hideShowUpdated">
            <h5 class="lbl-color">Updated at :</h5>
            <p id="groupShowUpdatedAt"></p>
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
      url: "groups/"+id,
      dataType: "JSON",
      data: {"id": id},
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug on send
        console.log(data);
        let boolCheck = false;
        data.forEach(row => {            
          if(row['statusCode'] != undefined && row['statusCode'] === 200) {
            boolCheck = true;
          } 
        });
        if(boolCheck) {
          $('#groupShowTable').html('');
          data.forEach(row => {            
            if(row['group'] != undefined) {
              console.log(row['group']);
              $('#showModalLabel').html("Group "+row['group']['name']);
              $('#groupShowCreator').html(row['group']['creator']);
              $('#groupShowCreatedAt').html(row['group']['created_at']);
              if(row['group']['updated_at'] === "none") {
                $('#hideShowUpdated').hide();
              } else {
                $('#groupShowUpdatedAt').html(row['group']['updated_at']);
                $('#hideShowUpdated').show();                
              }
              $('#showModal').modal('show');              
            } else if(row['partner'] != undefined) {
              console.log(row['partner']);
              $('#groupShowTable').append("<tr><td>"+row['partner']['company']+"</td><td>"+row['partner']['origin']+"</td><td>"+row['partner']['phone']+"</td><td>"+row['partner']['email']+"</td></tr>");
            }
          });
        } else if(row['statusCode'] != undefined && row['statusCode'] === 400) {
          toastr.warning("The specified group has not been found. Try to reload the page.");
          console.log(row['error']);
        }
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });
  }
</script>