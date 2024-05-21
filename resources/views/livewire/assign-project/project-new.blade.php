<x-livewiremodal-modal>
    <form wire:submit.prevent="projectStore">
        <div class="border bg-light rounded p-4">
            <div class="form-group">
                <label for="inputEmail4">Project Title <span class="text-danger">*</span></label>

                <input type="text" wire:model.defer="state.title"
                    class="form-control @error('title') is-invalid @enderror" id="" aria-describedby="emailHelp"
                    placeholder="Enter project title">

                @error('title')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group col">
                    <label for="inputEmail4">Project PDF <span class="text-danger">*</span></label>

                    <input type="file" wire:model.defer="state.file_type"
                        class="form-control @error('file_type') is-invalid @enderror" id="" aria-describedby="emailHelp"
                        placeholder="Enter file_type">

                    @if ($errors->has('file_type'))
                    <div class="text-danger"><small>{{ $errors->first('file_type') }}</small></div>
                    @endif
                </div>
                <div class="form-group col">
                    <label for="inputEmail4">Developers <span class="text-danger">*</span></label>
                    <select class="tom" wire:model.defer="state.name" multiple placeholder="Select a state..."
                        autocomplete="off">
                        <option value="">Select a state...</option>
                        @foreach ($employees as $employee)
                        <option value="{{$employee->id}}">{{$employee->name}}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('name'))
                    <div class="text-danger"><small>{{ $errors->first('name') }}</small></div>
                    @endif
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label for="inputEmail4">Start Date <span class="text-danger">*</span></label>
                    <input type="date" wire:model.defer="state.start_date"
                        class="form-control @error('start_date') is-invalid @enderror" aria-describedby="emailHelp"
                        placeholder="Enter start_date">
                    @error('start_date')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="form-group col">
                    <label for="inputEmail4">End Date <span class="text-danger">*</span></label>
                    <input type="date" wire:model.defer="state.end_date"
                        class="form-control @error('end_date') is-invalid @enderror" aria-describedby="emailHelp"
                        placeholder="Enter end_date">
                    @error('end_date')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

            </div>


            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click.prevent="projectStore" wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading.remove wire:target="projectStore">
                        <span><i class="fas fa-save mr-1"></i> Submit</span>
                    </div>

                    <div wire:loading="projectStore" wire:target="projectStore">
                        <div class="d-flex gap-2 align-items-center">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="ml-1">Loading...</span>
                        </div>
                    </div>
                </button>
            </div>
    </form>

</x-livewiremodal-modal>