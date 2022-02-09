@extends('admin.navbar')

@section('content')

<style>
  .ms-container {
    width: 100%;
  }
</style>

<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Countries Parameters</h1>
  </div>
  <br>

  <div class="container">
    <div class="form-group">
      <select id="multiselectCountries" name="countriesId[]" multiple="multiple" required="required"></select>      
    </div>
    <button type="button" class="btn btn-primary" onclick="console.log('');"></button>
  </div>

</div>

<script>
  
  // Get the country database and displays it in the page 
  $(function () {
    $.ajax({
      async: true,
      type: "GET",
      url: "country/getCountries",     
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug the response
        data = JSON.parse(data);
        console.log(data);     
        if(data['statusCode'] === 200) {
          // Build HTML <options> with the activated and disabled countries of the database
          let divAct = "";
          // let act = JSON.parse(data['activated']);
          if(data === "[]") {
          toastr.error("Country retrieving error. There does not seem to be any country in the database.");
          return 1;
        } else {
          $('#multiselect').empty();

          // Populate multiselect options
          data.options.forEach(item => {            
            $('#multiselectCountries').append(item);
          });
          
          $('#multiselectCountries').multiSelect({
            selectableHeader: "<div class='custom-header' style='color: var(--bs-body-color);'>Disabled countries</div><input type='text' class='search-input src-grp' autocomplete='off' placeholder='Search'>",
            selectionHeader: "<div class='custom-header' style='color: var(--bs-body-color);'>Activated countries</div><input type='text' class='search-input src-grp' autocomplete='off' placeholder='Search'>",
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
            afterSelect: function(){
              this.qs1.cache();
              this.qs2.cache();
            },
            afterDeselect: function(){
              this.qs1.cache();
              this.qs2.cache();
            }
          });
          $('#multiselectCountries').multiSelect('refresh');
        }


          console.log(data['activated']);
        } else if(data['statusCode'] === 400 && data['error']) {
          toastr.warning("There has been an error during the edition of the partner. Please retry to send your edition.");          
        } else if(data['statusCode'] === 400) {
          toastr.warning("There is an error with the country database, please report it to technical support.");
          console.log(data['error']);
        }
      },
      error: function(request, status, error) {
        console.log(error);
      }
    });
  });
</script>


@endsection