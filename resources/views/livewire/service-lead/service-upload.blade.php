<x-livewiremodal-modal>
    <form wire:submit.prevent="serviceStoreFile">
        <a href="{{asset('/service-lead-demo.csv')}}" class="btn btn-primary btn-sm mb-3"><i
                class="fa-solid fa-download"></i>
            Download
            Demo
        </a>
        <div class="border bg-light rounded p-4">
            <div class="form-group col">
                <label for="formFileMultiple" class="form-label">Upload CSV File</label>
                <input wire:model.defar='fileInfo'
                    class="form-control pb-5 pt-4 @error('fileInfo') is-invalid @enderror" type="file"
                    id="formFileMultiple" accept=".csv">
                @error('fileInfo')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>


            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button wire:click.prevent="serviceStoreFile" wire:loading.attr="disabled" class="btn btn-primary">
                    <div wire:loading.remove wire:target="serviceStoreFile">
                        <span><i class="fas fa-save mr-1"></i> Submit</span>
                    </div>

                    <div wire:loading="serviceStoreFile" wire:target="serviceStoreFile">
                        <div class="d-flex gap-2 align-items-center">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="ml-1">Loading...</span>
                        </div>
                    </div>
                </button>
            </div>
    </form>

</x-livewiremodal-modal>