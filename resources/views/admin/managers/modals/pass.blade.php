<div class="modal fade" id="editpassmanagermodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('admin/users/password') }}" method="post">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" idmanager = >Edit a manager</h5>
                    <input id="pass_id_manager" name="pass_id_manager" value="" hidden/>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleFormControlInput1">New Password :</label>
                            <input type="text" class="form-control" id="password_edit_manager" name="password_edit_manager">
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>

      
    </div>
</div>