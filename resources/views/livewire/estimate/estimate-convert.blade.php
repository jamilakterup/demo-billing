<form wire:submit.prevent="estimateConvert">
    <div class="border bg-light rounded p-4">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Customer <span class="text-danger">*</span></label>
                <input type="text" value="{{$customer->name}}" class="form-control" id="" aria-describedby="emailHelp"
                    placeholder="Enter invoice number" disabled>

                @if($errors->has('customer_id'))
                <div class="text-danger text-sm"><small>{{ $errors->first('customer_id') }}</small></div>
                @endif
            </div>
            {{-- {{dd($state)}} --}}
            <div class="form-group col-md-3">
                <label for="inputEmail4">Quotation Number <span class="text-danger">*</span></label>
                <input type="text" wire:model.defer="state.number" class="form-control" id=""
                    aria-describedby="emailHelp" placeholder="Enter invoice number" disabled>

                @if($errors->has('number'))
                <div class="text-danger text-sm"><small>{{ $errors->first('number') }}</small></div>
                @endif
            </div>

            <div class="form-group col-md-3">
                <label for="invoice_date">Invoice Date <span class="text-danger">*</span></label>
                <input type="date" wire:model.defer="state.date"
                    class="form-control @error('date') is-invalid @enderror" name="invoice_date" placeholder="YY-mm-dd">
                @if($errors->has('date'))
                <div class="text-danger text-sm"><small>{{ $errors->first('date') }}</small></div>
                @endif
            </div>

            <div class="form-group col-md-3">
                <label for="expected_payment_date">Expected Payment Date <span class="text-danger">*</span></label>
                <input type="date" wire:model.defer="state.expected_payment_date"
                    class="form-control @error('expected_payment_date') is-invalid @enderror"
                    name="expected_payment_date" placeholder="YY-mm-dd">
                @if($errors->has('expected_payment_date'))
                <div class="text-danger text-sm"><small>{{ $errors->first('expected_payment_date') }}</small></div>
                @endif
            </div>

        </div>

        <div class="form-row">

            <div class="form-group col-md-3">
                <label>Invoice Type <span class="text-danger">*</span></label>

                <select class="form-control @error('invoice_type_id') is-invalid @enderror"
                    wire:model.defer="state.invoice_type_id" wire:change="invoiceType($event.target.value)">
                    <option value="">--Please Select--</option>
                    @foreach($invoiceTypes as $invoiceType)
                    <option value="{{$invoiceType['id']}}">{{$invoiceType['invoice_type_name']}}</option>
                    @endforeach
                </select>


                @if($errors->has('invoice_type_id'))
                <div class="text-danger text-sm"><small>{{ $errors->first('invoice_type_id') }}</small></div>
                @endif
            </div>

            <div class="form-group col-md-3">
                <label for="inputEmail4">Recurring <span class="text-danger">*</span></label>

                <select wire:model.defer="state.type" id="type"
                    class="form-control @error('type') is-invalid @enderror">
                    <option value="">--Please Select--</option>
                    <option value="1">Recurring</option>
                    <option value="0">One time</option>
                </select>

                @if($errors->has('type'))
                <div class="text-danger text-sm"><small>{{ $errors->first('type') }}</small></div>
                @endif
            </div>


            <div class="col-md-6" id="interval-container" style="<?php
                    if(isset($state['type']) && $state['type']==1){
                        echo 'display:block';
                    }
                    else{
                        echo 'display:none';
                    }
                    ?>">
                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Recurring interval </label>
                        <select wire:model.defer="state.recurring_interval" id="interval"
                            class="form-control @error('recurring_interval') is-invalid @enderror">
                            <option value="">--Please Select--</option>
                            <option value="30">Monthly</option>
                            <option value="90">Quarterly</option>
                            <option value="182">Half Yearly</option>
                            <option value="365">Yearly</option>
                        </select>

                        @error('recurring_interval')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="recurring_start_date">Recurring Start Date</label>
                        <input type="date" wire:model.defer="state.recurring_start_date"
                            class="form-control @error('recurring_start_date') is-invalid @enderror"
                            name="recurring_start_date" placeholder="YY-mm-dd">
                        @if($errors->has('recurring_start_date'))
                        <div class="text-danger text-sm"><small>{{ $errors->first('recurring_start_date') }}</small>
                        </div>
                        @endif
                    </div>
                </div>

            </div>


        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="subject">Subject</label>
                <textarea class="form-control @error('subject') is-invalid @enderror" wire:model.defer="state.subject"
                    id="subject"></textarea>
                @if($errors->has('subject'))
                <div class="text-danger text-sm"><small>{{ $errors->first('subject') }}</small></div>
                @endif
            </div>
            <div class="form-group col-md-6">
                <label for="subject">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                    wire:model.defer="state.description" id="description"></textarea>
                @if($errors->has('description'))
                <div class="text-danger text-sm"><small>{{ $errors->first('description') }}</small></div>
                @endif
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="vat_text_visibility">Vat & Tax Text visibility</label>
                <select class="form-control @error('vat_text_visibility') is-invalid @enderror"
                    wire:model.defer="state.vat_text_visibility" id="vat_text_visibility">
                    <option value="">-Please select-</option>
                    <option value="All prices are including VAT & TAX.">All prices are including VAT & TAX.</option>
                    <option value="All prices are excluding VAT & TAX.">All prices are excluding VAT & TAX.</option>
                    <option value="VAT & TAX. Paid by">VAT & TAX. Paid by</option>
                    <option value="None">None</option>
                </select>
                @if($errors->has('vat_text_visibility'))
                <div class="text-danger text-sm"><small>{{ $errors->first('vat_text_visibility') }}</small></div>
                @endif
            </div>
            <div class="form-group col-md-4">
                <label for="date_visibility">Invoice date visibility</label>
                <select class="form-control @error('date_visibility') is-invalid @enderror"
                    wire:model.defer="state.date_visibility" id="date_visibility">
                    <option value="">-Please select-</option>
                    <option value="1">Visible</option>
                    <option value="0">Invisible</option>
                </select>
                @if($errors->has('date_visibility'))
                <div class="text-danger text-sm"><small>{{ $errors->first('date_visibility') }}</small></div>
                @endif
            </div>
            <div class="form-group col-md-4">
                <label for="auto_seal_signature">Auto seal & signature</label>
                <select class="form-control @error('auto_seal_signature') is-invalid @enderror"
                    wire:model.defer="state.auto_seal_signature" id="auto_seal_signature">
                    <option value="">-Please select-</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
                @if($errors->has('auto_seal_signature'))
                <div class="text-danger text-sm"><small>{{ $errors->first('auto_seal_signature') }}</small></div>
                @endif
            </div>
        </div>
    </div>


    <div class="text-center py-5">
        <button type="submit" class="btn btn-primary px-5">
            Convert Quotation
        </button>
    </div>

</form>