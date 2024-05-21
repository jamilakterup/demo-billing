<x-livewiremodal-modal>
    <form wire:submit.prevent="LeadStore">
        <div class="border bg-light rounded p-4">
            <div class="form-row">
                <div class="form-group col">
                    <label for="name">Name<span class="text-danger">*</span></label>

                    <input type="text" wire:model.defer="state.name"
                        class="form-control @error('name') is-invalid @enderror" id="name" aria-describedby="emailHelp"
                        placeholder="Enter Lead name">

                    @error('name')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="phone">Phone<span class="text-danger">*</span></label>

                    <input type="text" wire:model.defer="state.phone"
                        class="form-control @error('phone') is-invalid @enderror" id="phone"
                        aria-describedby="emailHelp" placeholder="Enter Lead phone">

                    @error('phone')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col">
                    <label for="email">Email</label>

                    <input type="text" wire:model.defer="state.email"
                        class="form-control @error('email') is-invalid @enderror" id="email"
                        aria-describedby="emailHelp" placeholder="Enter Lead email">

                    @error('email')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="source">Source<span class="text-danger">*</span></label>

                    <input type="text" wire:model.defer="state.source"
                        class="form-control @error('source') is-invalid @enderror" id="source"
                        aria-describedby="emailHelp" placeholder="Enter Lead source">

                    @error('source')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>


            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click.prevent="LeadStore" wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading.remove wire:target="LeadStore">
                        <span><i class="fas fa-save mr-1"></i> Submit</span>
                    </div>

                    <div wire:loading="LeadStore" wire:target="LeadStore">
                        <div class="d-flex gap-2 align-items-center">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="ml-1">Loading...</span>
                        </div>
                    </div>
                </button>
            </div>
    </form>

</x-livewiremodal-modal>