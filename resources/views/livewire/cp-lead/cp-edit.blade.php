<x-livewiremodal-modal>
    <form wire:submit.prevent="cpUpdate">
        <div class="border bg-light rounded p-4">
            <div class="row">
                <div class="form-group col">
                    <label for="inputEmail4">Status<span class="text-danger">*</span></label>

                    <input type="text" wire:model.defer="state.status"
                        class="form-control @error('status') is-invalid @enderror" id="" aria-describedby="statusHelp"
                        placeholder="Enter Lead status">

                    @error('status')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>

                <div class="form-group col">
                    <label for="inputEmail4">Consultant<span class="text-danger">*</span></label>

                    <input type="text" wire:model.defer="state.consultant"
                        class="form-control @error('consultant') is-invalid @enderror" id=""
                        aria-describedby="consultantHelp" placeholder="Enter consultant">

                    @error('consultant')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
                <div class="form-group col">
                    <label for="inputEmail4">Next Followup<span class="text-danger">*</span></label>

                    <input type="date" wire:model.defer="state.date"
                        class="form-control @error('date') is-invalid @enderror" id="" aria-describedby="dateHelp"
                        placeholder="Enter next followup date">

                    @error('date')
                    <div class="invalid-feedback">{{$message}}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="inputEmail4">Comment<span class="text-danger">*</span></label>
                <textarea class="form-control @error('comment') is-invalid @enderror" wire:model.defer="state.comment"
                    cols="30" rows="5"></textarea>
                @error('comment')
                <div class="invalid-feedback">{{$message}}</div>
                @enderror
            </div>
        </div>


        <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button wire:click.prevent="cpUpdate" wire:loading.attr="disabled" class="btn btn-primary">
                <div wire:loading.remove wire:target="cpUpdate">
                    <span><i class="fas fa-save mr-1"></i> Submit</span>
                </div>

                <div wire:loading="cpUpdate" wire:target="cpUpdate">
                    <div class="d-flex gap-2 align-items-center">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="ml-1">Loading...</span>
                    </div>
                </div>
            </button>
        </div>
    </form>

</x-livewiremodal-modal>

<script>
    $(document).ready(function(){
            $('.date').datetimepicker({
                  format:'Y-m-d',
                  timepicker:false,
                  formatDate:'Y-m-d',
                  onChangeDateTime:function(dp,$input){
                    @this.set('state.date', $input.val());
                    if($input.attr('name')=="invoice_date"){
                        @this.set('state.date', $input.val());
                    }
                    else if($input.attr('name')=="expected_payment_date"){
                        @this.set('state.expected_payment_date', $input.val());
                    }
                    else if($input.attr('name')=="recurring_start_date"){
                        @this.set('state.recurring_start_date', $input.val());
                    }
                  }
              }); 
        });
       
</script>