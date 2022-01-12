@extends('admin.navbar')

@section('content')

<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Countries Parameters</h1>
  </div>
  <br>

  <div class="row">
    <h2>Disabled Countries :</h2>
    <div id="disabledCountries"></div>
  </div>
  <div class="row">  
    <h2>Activated Countries :</h2>
    <div id="activatedCountries"></div>    
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
          /// Build HTML <li> with the activated and disabled countries of the database
          let divAct = "";
          // let act = JSON.parse(data['activated']);
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