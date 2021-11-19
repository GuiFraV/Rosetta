<div class="modal fade" id="addhoraire" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.horaires.store') }}" method="post">
            @csrf
            @method('POST')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" idmanager = >Add a time</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Time</label>
                            <input type="text" class="form-control" id="first_name_add_manager" name="hour_add_hour">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Agency</label>
                            <select  class="form-control" id="agency_add_manager" name="agency_add_hour">
                                @foreach ($agencies as $item)
                                    <option value="{{$item->id}}">{{$item->agency_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Type of Manager :</label>
                            <input class="form-check-input" type="radio" name="manager_add_hour" id="LM_add_hour" value="LM">
                            <label class="form-check-label" for="LM_add_hour">
                                Logistic Manager
                            </label>
                            <input class="form-check-input" type="radio" name="manager_add_hour" id="TM_add_hour" value="TM">
                            <label class="form-check-label" for="TM_add_hour">
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