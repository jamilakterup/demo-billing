<!-- Modal -->
<div class="modal fade" wire:ignore.self id="employee-create-modal" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{$isEdit?'Edit':'Create'}} Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form wire:submit.prevent="{{$isEdit?'employeeUpdate':'employeeStore'}}">
        <div class="modal-body">
          <div class="form-group">
            <label for="inputEmail4">Employee Name <span class="text-danger">*</span></label>
            <input type="text" wire:model.defer="state.name" class="form-control @error('name') is-invalid @enderror"
              id="" aria-describedby="emailHelp" placeholder="Enter number">
            @error('name')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>
          <div class="form-group">
            <label for="inputEmail4">Email</label>
            <input type="text" wire:model.defer="state.email" class="form-control @error('email') is-invalid @enderror"
              id="" aria-describedby="emailHelp" placeholder="Enter number">
            @error('email')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>
          <div class="form-group">
            <label for="inputEmail4">Phone Number</label>
            <input type="text" wire:model.defer="state.phone" class="form-control @error('phone') is-invalid @enderror"
              id="" aria-describedby="emailHelp" placeholder="Enter number">
            @error('phone')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="inputEmail4">Designation <span class="text-danger">*</span></label>

            <select wire:model.defer="state.designation_id" name="disignation_id"
              class="form-control @error('designation_id') is-invalid @enderror">
              <option value="">--Please Select--</option>
              @foreach ($designation_array as $key=>$value)
              <option value="{{$key}}">{{$value}}</option>
              @endforeach
            </select>
            @error('designation_id')
            <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>
          <div class="form-group">
            <label for="exampleInputFile">Signature</label>
            <div class="d-flex">
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" wire:model="state.signature" class="custom-file-input" id="exampleInputFile">
                  <label class="custom-file-label" for="exampleInputFile">
                    @if (isset($state['signature']))
                    {{$state['signature']->getClientOriginalName()}}
                    @else

                    Choose file 
                    @endif
                  </label>
                </div>
              </div>
              <div class="ml-3">
              </div>
            </div>



            @if ($errors->has('signature'))
            <div class="text-danger"><small>{{ $errors->first('signature') }}</small></div>
            @endif
          </div>


        </div>
        <div class="modal-footer justify-content-center">
          <button type="submit" class="btn btn-primary">{{$isEdit?'Update':'Submit'}}</button>
        </div>
      </form>
    </div>
  </div>
</div>