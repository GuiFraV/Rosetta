@extends('manager.navbar')

@section('content')
<div class="container">
  <br>
  <div class="jumbotron text-center">
    <h1 class="display-5" style="font-family: Segoe UI;">Groups Management</h1>
  </div>
  <br>        
  <a class="btn btn-primary" id="btnNewGroup" role="button">Create a new group</a><br><br>  
  <table class="table table-striped table-hover yajra-datatable" id="groupDataTable">
    <thead>
      <tr>
        {{-- <th scope="col">Number</th> --}}
        <th scope="col">Name</th>
        <th scope="col">Nb</th>
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

<style>
  .src-grp {
    border: 1px solid #ced4da;
  }
</style>

@include('manager.groups.modals.create')
@include('manager.groups.modals.show')
@include('manager.groups.modals.edit')
@include('manager.groups.modals.destroy')

<script>
  $(function () {
    var table = $('#groupDataTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('manager.groups.getGroups') }}",
      order: [[3, 'desc']],
      columns: [
        // {data: 'id', name: 'id'},
        {data: 'groupName', name: 'groupName'},
        {data: 'nb', name: 'nb'},  
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

  $('#btnNewGroup').on('click', function() {
    $.ajax({
      async: true,
      type: "GET",
      url: "groups/openModalNew",
      cache: false,
      processData: false,
      contentType: false,    
      success: function(data) {
        /// Debug JSON response
        // console.log(data);
        if(data === "[]") {
          toastr.error("You don't have any partner to create a new group.");
          return 1;
        } else {
          $('#groupName').val('');
          $('#multiselect').empty();
          // console.log(options);
          let options = JSON.parse(data);          
          options.forEach(row => {            
            $('#multiselect').append("<option value='"+row['value']+"'>"+row['label']+"</option>");
          });
          
          $('#multiselect').multiSelect({
            selectableHeader: "<div class='custom-header' style='color: var(--bs-body-color);'>Available partners</div><input type='text' class='search-input src-grp' autocomplete='off' placeholder='Search'>",
            selectionHeader: "<div class='custom-header' style='color: var(--bs-body-color);'>Selected partners</div><input type='text' class='search-input src-grp' autocomplete='off' placeholder='Search'>",
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

          $('#multiselect').multiSelect('refresh');
          $('#createGroupModal').modal('show');
        }
      },
      error: function (request, status, error) {
        console.log(error);
      }
    });
  });
</script>

@endsection