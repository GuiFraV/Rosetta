@extends('manager.navbar')

@section('content')
<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Email Management</h1>
  </div>
  <br>
  
  {{-- @include('message.opMessage') --}}
  {{-- @include('mails.modals.send') --}}
  {{-- @include('message.modals.message') --}}

  
      {{-- <nav class="navbar navbar-light bg-light"> --}}
          {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#messageSendModal">Open PopUp</button> --}}
          {{-- <a class="btn btn-outline-success" href="/lastMail">Get last</a> --}}
          
              
                  
                  {{-- <button class="btn btn-outline-success" type="submit">Add Group</button> --}}
                  {{-- <a class="btn btn-outline-success" href="/groups/create" type="submit">Add Group</a> --}}
                  {{-- <div class="float-end"> --}}
                      <a class="btn btn-primary" id="btnNewMail" role="button">Create a new email</a><br><br>
                      {{-- <a class="btn btn-primary" id="btnNewMail" data-bs-toggle="modal" href="#createMailModal" role="button">Create a new email</a><br><br> --}}
                  {{-- </div> --}}
                  {{-- float-end btn btn-primary --}}
              
          
          {{-- 
          <div class="container col-sm-4">
              <form class="d-flex">
                  <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"  autocomplete="off"/>
                  <br/>
                  <button class="btn btn-outline-success" type="submit">Search</button>
              </form>
          </div>
          --}}
      {{-- </nav> --}}
  


  {{-- New email DataTable (Yajra) --}}
  <table class="table table-striped table-hover yajra-datatable" id="emailDataTable">
    <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">Subject</th>
        <th scope="col">Automatic Sending</th>
        <th scope="col">Author</th>
        <th scope="col">Created At</th>
        <th scope="col">Updated At</th>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody class="align-middle"></tbody>
  </table><br>
</div>

@include('manager.mails.modals.create')
@include('manager.mails.modals.show')
@include('manager.mails.modals.edit')
@include('manager.mails.modals.destroy')

<script type="text/javascript">
  
  
  var isFirstLoad = 1;  
  $('#btnNewMail').on('click', function() {
    // If it is the load of the page, clears the sujet and message precedent content
    if(isFirstLoad) {
      $('#object').val('');
      tinymce.get('message').setContent(''); 
      isFirstLoad--;
    }
    $('#createMailModal').modal('show');
  });

  $(function () {
    var table = $('#emailDataTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('manager.mails.getMails') }}",
      //order: [[1, 'desc']],
      columns: [
        {data: 'id', name: 'id'},
        {data: 'object', name: 'object'},
        {data: 'autoSend', name: 'autoSend'},
        {data: 'author', name: 'author'},
        {data: 'created_at', name: 'created_at'},
        {data: 'updated_at', name: 'updated_at'},
        {
            data: 'showBtn', 
            name: 'showBtn', 
            orderable: false, 
            searchable: true
        },
        {
            data: 'editBtn', 
            name: 'editBtn', 
            orderable: false, 
            searchable: true
        },
        {
            data: 'deleteBtn', 
            name: 'deleteBtn', 
            orderable: false, 
            searchable: true
        }
      ]
    });
  });
</script>

{{--
<script>
    setTimeout(function () {
        // document.getElementById('messagealert').style.display = '';
        $("#messagealert").hide();
    }, 1000);
        
</script>
--}}

{{--
<script>
  function myFunction() {
    // var checkBox = document.getElementById("sendType1");
    var checkBox1 = document.getElementById("sendType3");
    // var text = document.getElementById("divTime");
    var text1 = document.getElementById("divDate");
    if (checkBox1.checked == true){
      text1.style.display = "block";
      // text1.style.display = "none";
    // } else if (checkBox1.checked == true) {
      
    //   text.style.display = "none";
    //   text1.style.display = "block";
    } else {
      
      // text.style.display = "none";
      text1.style.display = "none";
    }
  }
  </script>
--}}

{{-- 
<script type="text/javascript">
  function editFunc(clicked_id)
  {

        // get the data
    // var id = JSON.stringify(clicked_id["id"]).replaceAll('"', '');
    var object = JSON.stringify(clicked_id["object"]).replaceAll('"', '');
    var message = JSON.stringify(clicked_id["message"]).replaceAll('"', '');
    // var form = document.getElementById("form_edit");
    
    // form.action = {{ route("mails.update",['mail' => id]) }};
      // fill the data in the input fields
    // document.getElementById("id").value = id;
    document.getElementById("modal-input-object").value = object;
    document.getElementById("modal-input-message").value = message;
  }
  
</script> 
--}}


{{--
<script type="text/javascript">
  
  function editLast(clicked_id)
    {
      $("#btn_update").attr("disabled", false);
      $("#sendMailUpdate").attr("hidden", true);
      // $("#btn_send").attr("hidden", true);
      // alert (JSON.stringify(clicked_id));
          // get the data
        var id = JSON.stringify(clicked_id["id"]).replaceAll('"', '');
        var object = JSON.stringify(clicked_id["object"]).replaceAll('"', '');
        var message = JSON.stringify(clicked_id["message"]).replaceAll('"', '');
        
        // fill the data in the input fields
      document.getElementById("id").value = id;
      document.getElementById("modal-input-object").value = object;
      // document.getElementById("modal-input-message").value = message;
      tinymce.get("modal-input-message").setContent(message);
      document.getElementById("mailId1").value = id;

    }
    function openUp(){
    $('#messageModal').modal('show');
    }
  // function sendFunc(clicked_id) {
  //   // alert(clicked_id["id"]);
  //   var id = JSON.stringify(clicked_id["id"]).replaceAll('"', '');
  //   document.getElementById("mailId1").value = id;
  // }
</script>
--}}

@endsection