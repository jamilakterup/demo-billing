


  <!-- Modal -->
  <div class="modal fade" wire:ignore.self id="permission-create-modal"   tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{$isEdit?'Edit':'Create'}} Permission</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form wire:submit.prevent="{{$isEdit?'permissionUpdate':'permissionStore'}}">
            <div class="modal-body">
                    <div class="form-group">
                        <label for="inputEmail4">Module name</label>
                        <select wire:model.defer="state.module_id"  class="form-control @error('module_id') is-invalid @enderror">
                            <option value="">-Please Select-</option>
                            @foreach ($allModule as $key=>$value)
                                <option value="{{$key}}">{{ucfirst($value)}}</option>
                            @endforeach
                        </select>
                        @error('module_id')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="inputEmail4">Permission name</label>
                        <input type="text" wire:model.defer="state.name"  class="form-control @error('name') is-invalid @enderror" id="" aria-describedby="emailHelp" placeholder="Enter permission name">
                        @error('name')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>


            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">{{$isEdit?'Update':'Submit'}}</button>
            </div>
        </form>
      </div>
    </div>
  </div>
