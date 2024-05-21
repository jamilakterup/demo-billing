


  <!-- Modal -->
  <div class="modal fade" wire:ignore.self id="status-create-modal"   tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{$isEdit?'Edit':'Create'}} Status</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form wire:submit.prevent="{{$isEdit?'statusUpdate':'statusStore'}}">
            <div class="modal-body">
                    <div class="form-group">
                        <label for="inputEmail4">Type</label>
                        <select wire:model.defer="state.type"  class="form-control @error('type') is-invalid @enderror">
                            <option value="">-Please Select-</option>
                            <option value="estimate">Estimate</option>
                            <option value="invoice">Invoice</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="inputEmail4">Name</label>
                        <input type="text" wire:model.defer="state.name"  class="form-control @error('name') is-invalid @enderror" id="" aria-describedby="emailHelp" placeholder="Enter name">
                        @error('name')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="inputEmail4">Color</label>
                        <input type="text" wire:model.defer="state.color"  class="form-control @error('color') is-invalid @enderror" id="" aria-describedby="emailHelp" placeholder="Enter name">
                        @error('color')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

            </div>
            <div class="modal-footer justify-content-center">
                <button type="submit" class="btn btn-primary">{{$isEdit?'Update':'Submit'}}</button>
            </div>
        </form>
      </div>
    </div>
  </div>
