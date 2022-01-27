<div class="modal fade" id="matchingModal" tabindex="-1" aria-labelledby="matchingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="matchingModalLabel">Match a <span id="LTTitle"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        <input type="hidden" id="idInitialElementMatch">
        <p><span id="LTLabel"></span> to match :</p>
        <div class="list-group" id="initialMatch"></div>
        <br>
        <label for="maxKilometersMatch" class="form-label">Maximum distance between the Load and Truck departure place.</label>
        <input type="range" class="form-range" min="10" max="150" value="150" step="5" id="maxKilometersMatch" onchange="$('#actualRangeVal').html($('#maxKilometersMatch').val()+'Km');">
        <div class="d-flex justify-content-end"><span id="actualRangeVal">150Km</span></div>                
        <br>
        <button type="button" class="btn btn-success float-end" onclick="refreshMatchModal($('#idInitialElementMatch').val())">Refresh</button>        
        <p>Best available matches :</p>        
        <div class="list-group" id="lsMatches"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="btnSendMatch">Match</button>        
      </div>
    </div>
  </div>
</div>

<script>
  $( "#matchingModal" ).on('shown', function(){
    console.log('open');
  });
  
  /// Visual management of the matching choice on click on a matching choice ///
  $(document).on('click','.list-group-item-action', function(e) {
    e.stopPropagation();
    e.stopImmediatePropagation();
    // Delete active class if exists on precedent chosen element
    $(".list-group-item-action").each(function() {
      $(this).removeClass("active");
    });
    // Set this choice as the active one
    e.target.classList.add("active");
  });

  function openMatchModal(id) {
    $.ajax({
      async: true,
      type: "POST",
      url: "trajets/getMatchingList/"+id,
      dataType: "JSON",
      data: {"id": id},
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {        
        if(data['error'] === 0) {  
          if(data['type'] === "load") {
            $('#LTTitle').html('load');
            $('#LTLabel').html('Load');
          } else if (data['type'] === "truck") {
            $('#LTTitle').html('truck');
            $('#LTLabel').html('Truck');            
          }
          $('#initialMatch').html(data['initialMatch']);
          $('#lsMatches').html(data["lsMatches"]);        
          $('#matchingModal').modal('show');
          return 0;              
        } else {
          toastr.warning(data['message']);
          return 1;
        }                                  
      },
      error: function (request, status, error) {
        console.log(error);
        return 1;
      }      
    });
  }

  function refreshMatchModal(id) {    
    let fd = new FormData();
    fd.append("id", id);
    fd.append("kmParam", $("#maxKilometersMatch").val())    
    $.ajax({
      async: true,
      type: "POST",
      url: "trajets/refreshMatchingList/"+id,
      dataType: "JSON",
      data: fd,
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {        
        if(data['error']) {                      
          $('#lsMatches').html('<p>There are no match for this distance parameter.</p>');          
          toastr.warning(data['message']);
          return 1;
        } else {          
          $('#lsMatches').html(data["lsMatches"]);
        }                                  
      },
      error: function (request, status, error) {
        console.log(error);
        return 1;
      }      
    });
  }

  $('#btnSendMatch').on('click', function() {   
    let fd = new FormData();
    let currentMatch = $('#idInitialElementMatch').val();
    let elementMatched = $('.active').val();
    fd.append("currentMatch", currentMatch);
    fd.append("elementMatched", elementMatched);
    $.ajax({
      async: true,
      type: "POST",
      url: "trajets/matchElements",
      dataType: "JSON",
      data: fd,
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        if(data['error']) {          
          toastr.warning(data['message']);
          return 1;
        } else {          
          $('#ln'+currentMatch).addClass("text-decoration-line-through");
          $('#ln'+elementMatched).addClass("text-decoration-line-through");
          toastr.success("These elements have been matched, you can still consult them in the Route list.");
          $('#matchingModal').modal('hide');
          $('#idInitialElementMatch').val('');
          $('.active').val('');
        }                                  
      },
      error: function (request, status, error) {
        console.log(error);
        return 1;
      }      
    });
  });
</script>