<div class="modal fade" id="createMailModal" data-bs-backdrop="static" aria-hidden="true" aria-labelledby="createMailModalLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMailModalLabel">New Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="appbundle_mailing" id="formNewMail" method="POST" action="{{Route('manager.mails.store')}}">
                @csrf
                <div class="modal-body" id="modal_content">
                    <div id="appbundle_mailing">
                        <br/>
                        <div class="form-group">
                            <label class="control-label required" for="object">Subject</label>
                            <input type="text" id="object" name="object" maxlength="255" class="form-control" required="required"/>
                        </div>
                        <br/>
                        <div class="form-group">
                            <label class="control-label required" for="message">Message</label>
                            <textarea class="mce-editor" id="message" name="message" cols="50" rows="5" class="id-"></textarea>
                        </div>
                    </div>
                    <br/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
                    {{-- Redirect the user to the same page (bad UX) <a class="btn btn-secondary" href="/mails">Close</a> --}}
                    <input class="btn btn-primary" id="createBt" type="submit" value="Create" />
                    {{-- Disabled so it is not worth showing <button class="btn btn-primary" id="sendBt" data-bs-target="#sendModal" data-bs-toggle="modal" data-bs-dismiss="modal" disabled>Send message</button> --}}
                    {{-- <a class="btn btn-primary" id="submit_button">Subscribe</a> --}}
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let formNewMail = $("#formNewMail");
    formNewMail.submit(function (e) {
        e.preventDefault(e);
        tinyMCE.triggerSave();
        let fd = new FormData(this);
        $.ajax({
            async: true,
            type: formNewMail.attr("method"),
            url: formNewMail.attr("action"),
            data: fd,
            cache: false,
            processData: false,
            contentType: false,    
            success: function(data) {
                /// Debug on send
                //console.log(JSON.parse(data)['data']);
                $('#object').val("");
                tinymce.get('message').setContent('');                                
                $('#emailDataTable').DataTable().ajax.reload();
                $('#createMailModal').modal('hide');
                toastr.success("The email has been created!")
            },
            error: function (request, status, error) {
                console.log("error");
            }
        });
    });

    /*
    $('a[typebtn="editpassmanagerbtn"]').on('click', function(e) {
        var mail_id = $(this).attr('mail_id');    
        // alert(JSON.stringify(mail_id));
        $('#id3').val(mail_id);
    });

    $(".btn-ok").click(function() {
        // alert('delete button');
        var mail_id = $('#id3').val();
        // e.preventDefault(e);
        // alert(JSON.stringify(mail));
        $.ajax({
            type: 'POST',
            url: '/mails/destroyAjax',
            data: {
                "_token": "{{csrf_token()}}",
                "id": mail_id,
                
            },
            success: function (){
                // console.log("it Works");
                $('#deleteModal').modal('hide');
                $('.mailRow'+mail_id).remove();
            }
        });
    });

    var frm = $("#form_edit");
    
    frm.submit(function (e) {
        
        e.preventDefault(e);
        tinyMCE.triggerSave();
        var formData = new FormData(this);
            
        $.ajax({
            async: true,
            type: frm.attr("method"),
            url: frm.attr("action"),
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function (data) {
                
                // alert(data);
                console.log("success");
                $('#modal-input-object').val("");
                // tinyMCE.activeEditor.setContent('');
                tinymce.get('modal-input-message').setContent('');
                $("#btn_update").attr("disabled", true);
                $("#btn_send").attr("disabled", false);
            },
            error: function (request, status, error) {
                console.log("error");
            },
        });
    });

    var sendForm = $("#sendForm");
    sendForm.submit(function (e) { 
        e.preventDefault(e);
        var formData = new FormData(this);
        //   document.getElementById('sendDate').removeAttribute('required');

        $.ajax({
            async: true,
            type: sendForm.attr("method"),
            url: sendForm.attr("action"),
            data: formData,
            cache: false,
            processData: false,
            contentType: false,

            success: function (data) {
                //   alert("success");
                $('#messageLabel').html("Email has been sent successfully!");  
                //   console.log("success"); 
                $('#messageModal').modal('show');
                setTimeout(function()
                {
                    $('#messageModal').modal('hide');
                }, 1000);
                $('#group_id').prop('selectedIndex',0);
            },
            error: function (request, status, error) {
                //   alert("error");
                $('#messageLabel').html("Email hasn't been sent!");
                $('#sendModal').modal('hide');
                //   console.log("error");    
                $('#messageModal').modal('show');
                setTimeout(function() {
                    $('#messageModal').modal('hide');
                }, 1000);        
            },
        });
    });

      $('a[typebtn="deleteUnsub"]').on('click', function(e) 
      {
        //   alert('heloooooooo');
          var unsub_Email = $(this).attr('unsub_Email');
          $('#unsubEmail').val(unsub_Email);
      });
    */


    //   $(".btn-ok").click(function()
    //   {
    //         var unsubEmail = $('#unsubEmail').val();
    //         $.ajax(
    //         {
    //             type: 'POST',
    //             url: '/mails/destroyAjax',
    //             data: {
    //                 "_token": "{{csrf_token()}}",
    //                 "email": mail_id,
                    
    //             },
    //             success: function (){
    //                 $('#deleteUnsubModal').modal('hide');
    //             }
    //         });
      
    //     });
</script>
