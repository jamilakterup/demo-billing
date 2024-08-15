<x-livewiremodal-modal>
    <form wire:submit.prevent="leadConvertToCustomer" enctype="multipart/form-data">
        @csrf
        <div class="border bg-light rounded p-4">

            <div class="form-row">
                <div class="form-group col">
                    <label for="name">Name<span class="text-danger">*</span></label>

                    <input type="text" wire:model.defer="state.name"
                        class="form-control @error('name') is-invalid @enderror" id="name"
                        aria-describedby="emailHelp" placeholder="Enter Lead name">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="display_name">Display Name<span class="text-danger">*</span></label>

                    <input type="text" wire:model.defer="state.display_name"
                        class="form-control @error('display_name') is-invalid @enderror" id="display_name"
                        aria-describedby="emailHelp" placeholder="Enter Lead Deiplay Name" required>

                    @error('display_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="phone">Phone<span class="text-danger">*</span></label>

                    <input type="text" wire:model.defer="state.phone"
                        class="form-control @error('phone') is-invalid @enderror" id="phone"
                        aria-describedby="emailHelp" placeholder="Enter Lead phone">

                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
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
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="source">Source<span class="text-danger">*</span></label>

                    <input type="text" wire:model.defer="state.source"
                        class="form-control @error('source') is-invalid @enderror" id="source"
                        aria-describedby="emailHelp" placeholder="Enter Lead source">

                    @error('source')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h6 class="mt-3">Company Info:</h6>
            <hr>

            <div class="form-row">
                <div class="form-group col">
                    <label for="company_name">Company Name<span class="text-danger">*</span></label>

                    <input type="text" wire:model.defer="state.company_name"
                        class="form-control @error('company_name') is-invalid @enderror" id="company_name"
                        aria-describedby="emailHelp" placeholder="Enter Lead Company Name">

                    @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="company_email">Company Email</label>

                    <input type="email" wire:model.defer="state.company_email"
                        class="form-control @error('company_email') is-invalid @enderror" id="company_email"
                        aria-describedby="company_emailHelp" placeholder="Enter Company Email">

                    @error('company_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="company_phone">Company Phone</label>

                    <input type="text" wire:model.defer="state.company_phone"
                        class="form-control @error('company_phone') is-invalid @enderror" id="company_phone"
                        aria-describedby="company_phoneHelp" placeholder="Enter Company Phone">

                    @error('company_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col">
                    <label for="company_web">Company Web</label>

                    <input type="text" wire:model.defer="state.company_web"
                        class="form-control @error('company_web') is-invalid @enderror" id="company_web"
                        aria-describedby="company_webHelp" placeholder="Enter company Web">

                    @error('company_web')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="company_address">Company Address</label>

                    <input type="text" wire:model.defer="state.company_address"
                        class="form-control @error('company_address') is-invalid @enderror" id="company_address"
                        aria-describedby="company_addressHelp" placeholder="Enter Lead company_address">

                    @error('company_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="company_logo">Company Logo</label>

                    <input type="file" wire:model="company_logo"
                        class="form-control @error('company_logo') is-invalid @enderror" id="company_logo"
                        aria-describedby="company_logoHelp" placeholder="Enter Lead company_logo">

                    @error('company_logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>



            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click.prevent="leadConvertToCustomer" wire:loading.attr="disabled"
                    class="btn btn-primary">
                    <div wire:loading.remove wire:target="leadConvertToCustomer">
                        <span><i class="fas fa-save mr-1"></i> Submit</span>
                    </div>

                    <div wire:loading="leadConvertToCustomer" wire:target="leadConvertToCustomer">
                        <div class="d-flex gap-2 align-items-center">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="ml-1">Loading...</span>
                        </div>
                    </div>
                </button>
            </div>
    </form>

</x-livewiremodal-modal>
