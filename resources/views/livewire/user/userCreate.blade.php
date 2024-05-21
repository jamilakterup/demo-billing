


  <!-- Modal -->
  <div class="modal fade" wire:ignore.self id="exampleModal"   tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Users</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form wire:submit.prevent="userStore">
                <div class="form-group">
                    <input type="text" wire:model="name" name="name" class="form-control" id="" aria-describedby="emailHelp" placeholder="Enter name">
                    @error('name')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="email" wire:model.defer="email" name="email" class="form-control" id="" aria-describedby="emailHelp" placeholder="Enter email">
                    @error('email')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>
                
                <div class="form-group">
                    <input type="password" wire:model.defer="password" name="password" class="form-control" id="" placeholder="Password">
                    @error('password')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>



