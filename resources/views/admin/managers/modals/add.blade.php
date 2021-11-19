<div class="modal fade" id="addmanagermodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.managers.store') }}" method="post">
            @csrf
            @method('POST')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" idmanager = >Add a manager</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                
                        <div class="form-group">
                            <label for="exampleFormControlInput1">First name</label>
                            <input type="text" class="form-control" id="first_name_add_manager" name="first_name_add_manager">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Last name</label>
                            <input type="text" class="form-control" id="last_name_add_manager" name="last_name_add_manager">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Email</label>
                            <input type="email" class="form-control" id="email_add_manager" name="email_add_manager">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Password</label>
                            <input type="text" class="form-control" id="password_add_manager" name="password_add_manager">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Signature</label>
                            <input type="text" class="form-control" id="signature_add_manager" name="signature_add_manager">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Agency</label>
                            <select  class="form-control" id="agency_add_manager" name="agency_add_manager">
                                @foreach ($agencies as $item)
                                    <option value="{{$item->id}}">{{$item->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Type of Manager :</label>
                            <input class="form-check-input" type="radio" name="manager_add_radios" id="LM_add_manager" value="LM">
                            <label class="form-check-label" for="LM_add_manager">
                                Logistic Manager
                            </label>
                            <input class="form-check-input" type="radio" name="manager_add_radios" id="TM_add_manager" value="TM">
                            <label class="form-check-label" for="TM_add_manager">
                                Transport Manager
                            </label>
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