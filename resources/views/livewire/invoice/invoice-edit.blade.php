<x-livewiremodal-modal>
    <form wire:submit.prevent="invoiceStore">
        <div class="border bg-light rounded p-4">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Customer <span class="text-danger">*</span></label>

                    <select class="tom-select @error('customer_id') is-invalid @enderror"
                        wire:model.defer="state.customer_id">
                        <option value="">--Please Select--</option>
                        @foreach ($customers as $key=>$value)
                        <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>


                    @if($errors->has('customer_id'))
                    <div class="text-danger text-sm"><small>{{ $errors->first('customer_id') }}</small></div>
                    @endif

                </div>

                <div class="form-group col-md-3">
                    <label for="inputEmail4">Invoice Number <span class="text-danger">*</span></label>
                    <input type="text" wire:model.defer="state.number" class="form-control" id=""
                        aria-describedby="emailHelp" placeholder="Enter invoice number" disabled>

                    @if($errors->has('number'))
                    <div class="text-danger text-sm"><small>{{ $errors->first('number') }}</small></div>
                    @endif
                </div>

                <div class="form-group col-md-3">
                    <label for="invoice_date">Invoice Date <span class="text-danger">*</span></label>
                    <input type="date" wire:model.defer="state.date"
                        class="form-control @error('date') is-invalid @enderror" name="invoice_date"
                        placeholder="YY-mm-dd">
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

                    <select class="tom-select @error('invoice_type_id') is-invalid @enderror"
                        wire:model.defer="state.invoice_type_id" wire:change="invoiceType($event.target.value)">
                        <option value="">--Please Select--</option>
                        @foreach($invoiceTypes as $invoiceType)
                        <option value="{{$invoiceType->id}}">{{$invoiceType->invoice_type_name}}</option>
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
                    <textarea class="form-control @error('subject') is-invalid @enderror"
                        wire:model.defer="state.subject" id="subject"></textarea>
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



        <hr>


        <div class="row">
            <div class="col-md-7">
                <div class="table table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Unit</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th style="width:120px">Sub Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\Cart::getContent() as $cart)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$cart->name}}</td>
                                <td>{{$cart->attributes['unit_name']}}</td>
                                <td>{{$cart->price}}</td>
                                <td>{{$cart->quantity}}</td>
                                <td>{{$cart->quantity*$cart->price}}</td>
                                <td>
                                    <a class="btn btn-outline-primary btn-sm"
                                        wire:click.prevent="productEdit({{$cart->id}})"><i class="fas fa-edit"></i></a>
                                    <a class="btn btn-outline-danger btn-sm del" type="edit" data="{{$cart->id}}"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" class="text-center">Total</td>
                                <td><input type="text" value="{{\Cart::getTotal()}}" class="form-control total"
                                        disabled /></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td colspan="5" class="text-center">Discount</td>
                                <td><input wire:model.defer="discount" type="text" class="form-control discount key_up">
                                </td>
                                <td>
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input wire:model.defer="discount_percent" type="checkbox"
                                                id="checkboxPrimary1" class="check discount_percent">
                                            <label for="checkboxPrimary1">
                                                %
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>



                            <tr>
                                <td colspan="5" class="text-center">Vat</td>
                                <td><input wire:model.defer="vat" type="text" class="form-control vat key_up"></td>
                                <td>
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input wire:model.defer="vat_percent" type="checkbox" id="checkboxPrimary2"
                                                class="check vat_percent">
                                            <label for="checkboxPrimary2">
                                                %
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="5" class="text-center">Tax</td>
                                <td><input wire:model.defer="tax" type="text" class="form-control key_up tax "></td>
                                <td>
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input wire:model.defer="tax_percent" type="checkbox" id="checkboxPrimary3"
                                                class="check tax_percent">
                                            <label for="checkboxPrimary3">
                                                %
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>




                            <tr>
                                <td colspan="5" class="text-center">Payment</td>
                                <td><input wire:model.defer="payment" type="text" class="form-control payment"></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td colspan="5" class="text-center">Due</td>
                                <td><input type="text" wire:model.defer="due" class="form-control due" disabled /></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-5">
                <div class="position-relative border bg-light rounded p-4">

                    <div class="loading-animation" wire:loading
                        wire:target="newProductToggle,productEdit,updateProduct,addProduct,itemAssign">
                        <div>
                            <img src="{{asset('dist/img/spinner.svg')}}" width="">
                        </div>
                    </div>
                    @if($isNewProduct)
                    <div class="row g-1 mb-3">
                        <label class="col-sm-4 col-form-label">Product <span class="text-danger">*</span> </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" wire:model.defer="newpro.name"
                                placeholder="Enter product name">
                            @if($errors->has('name'))
                            <div class="text-danger text-sm"><small>{{ $errors->first('name') }}</small></div>
                            @endif

                        </div>
                    </div>

                    <div class="row g-1 mb-3">
                        <label class="col-sm-4 col-form-label">Type <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="tom-select" wire:model.defer="newpro.product_type_id">
                                <option value="">-Please select type-</option>
                                @foreach ($productTypes as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>

                            @if($errors->has('product_type_id'))
                            <div class="text-danger text-sm"><small>{{ $errors->first('product_type_id') }}</small>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-1 mb-3">
                        <label class="col-sm-4 col-form-label">Unit <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="tom-select" wire:model.defer="newpro.unit_id">
                                <option value="">-Please select unit-</option>
                                @foreach ($units as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>

                            @if($errors->has('unit_id'))
                            <div class="text-danger text-sm"><small>{{ $errors->first('unit_id') }}</small></div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-1 mb-3">
                        <label class="col-sm-4 col-form-label">Price <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" wire:model.defer="newpro.product_price" class="form-control"
                                placeholder="Enter price">
                            @if($errors->has('product_price'))
                            <div class="text-danger text-sm"><small>{{ $errors->first('product_price') }}</small></div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-1 mb-3">
                        <label class="col-sm-4 col-form-label">Quantity <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" wire:model.defer="newpro.product_quantity" class="form-control"
                                placeholder="Enter quantity">
                            @if($errors->has('product_quantity'))
                            <div class="text-danger text-sm"><small>{{ $errors->first('product_quantity') }}</small>
                            </div>
                            @endif
                        </div>
                    </div>



                    <div class="row g-1 mb-3">
                        <div class="col-sm-4">

                        </div>
                        <div class="col-sm-8">
                            <button type="button" wire:click.prevent="addNewProduct" wire:loading.attr="disabled"
                                class="btn btn-primary">
                                <div wire:loading.remove wire:target="addNewProduct">
                                    <span><i class="fas fa-plus mr-1"></i> Add</span>
                                </div>

                                <div wire:loading="updateProduct" wire:target="addNewProduct">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <span class="ml-1">Loading...</span>
                                    </div>
                                </div>
                            </button>

                            <button type="button" wire:click.prevent="newProductToggle" wire:loading.attr="disabled"
                                class="btn btn-info">
                                <div wire:loading.remove wire:target="newProductToggle">
                                    <span><i class="fas fa-backward mr-1"></i> Back</span>
                                </div>

                                <div wire:loading="newProductToggle" wire:target="newProductToggle">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <span class="ml-1">Loading...</span>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="row g-1 mb-3">
                        <label class="col-sm-4 col-form-label">Product <span class="text-danger">*</span> <button
                                wire:click.prevent="newProductToggle" type="button" title="Add new product"
                                class="btn btn-light border py-0 px-2 ml-2">+</button></label>
                        <div class="col-sm-8">
                            <select class="tom-select" wire:model.defer="addpro.product_id"
                                wire:change="itemAssign($event.target.value)">
                                <option value="">-Please select item-</option>
                                @foreach ($products as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('product_id'))
                            <div class="text-danger text-sm"><small>{{ $errors->first('product_id') }}</small></div>
                            @endif

                        </div>
                    </div>

                    <div class="row g-1 mb-3">
                        <label class="col-sm-4 col-form-label">Unit <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="tom-select" wire:model.defer="addpro.unit">
                                <option value="">-Please select unit-</option>
                                @foreach ($units as $key=>$value)
                                <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>

                            @if($errors->has('unit'))
                            <div class="text-danger text-sm"><small>{{ $errors->first('unit') }}</small></div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-1 mb-3">
                        <label class="col-sm-4 col-form-label">Price <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" wire:model.defer="addpro.price" class="form-control"
                                placeholder="Enter price">
                            @if($errors->has('price'))
                            <div class="text-danger text-sm"><small>{{ $errors->first('price') }}</small></div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-1 mb-3">
                        <label class="col-sm-4 col-form-label">Quantity <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" wire:model.defer="addpro.quantity" class="form-control"
                                placeholder="Enter quantity">
                            @if($errors->has('quantity'))
                            <div class="text-danger text-sm"><small>{{ $errors->first('quantity') }}</small></div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-1 mb-3">
                        <label class="col-sm-4 col-form-label">Vat & Tax <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select wire:model.defer="addpro.vat_tax" class="form-control">
                                <option value="">-Please select-</option>
                                <option value="false">No</option>
                                <option value="true">Yes</option>
                            </select>
                            @if($errors->has('vat_tax'))
                            <div class="text-danger text-sm"><small>{{ $errors->first('vat_tax') }}</small></div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-1 mb-3">
                        <div class="col-sm-4">

                        </div>
                        <div class="col-sm-8">
                            @if($isEdit)
                            <button type="button" wire:click.prevent="updateProduct" wire:loading.attr="disabled"
                                class="btn btn-primary">
                                <div wire:loading.remove wire:target="updateProduct">
                                    <span><i class="fas fa-plus mr-1"></i> Update</span>
                                </div>

                                <div wire:loading="updateProduct" wire:target="updateProduct">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <span class="ml-1">Loading...</span>
                                    </div>
                                </div>
                            </button>
                            @else
                            <button type="button" wire:click.prevent="addProduct" wire:loading.attr="disabled"
                                class="btn btn-primary">
                                <div wire:loading.remove wire:target="addProduct">
                                    <span><i class="fas fa-plus mr-1"></i> Add</span>
                                </div>

                                <div wire:loading="addProduct" wire:target="addProduct">
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        <span class="ml-1">Loading...</span>
                                    </div>
                                </div>
                            </button>
                            @endif
                        </div>
                    </div>

                    @endif
                </div>
            </div>
        </div>



        <div class="modal-footer justify-content-center">
            @if(!Cart::isEmpty())
            <button type="button" wire:click.prevent="invoicePreview" wire:loading.attr="disabled" class="btn btn-info">
                <div wire:loading.remove wire:target="invoicePreview">
                    <span><i class="fas fa-eye mr-1"></i> Preview</span>
                </div>

                <div wire:loading="invoicePreview" wire:target="invoicePreview">
                    <div class="d-flex gap-2 align-items-center">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="ml-1">Loading...</span>
                    </div>
                </div>
            </button>

            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">
                <div wire:loading.remove wire:target="invoiceStore">
                    <span><i class="fas fa-save mr-1"></i> Update</span>
                </div>

                <div wire:loading="invoiceStore" wire:target="invoiceStore">
                    <div class="d-flex gap-2 align-items-center">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="ml-1">Loading...</span>
                    </div>
                </div>
            </button>

            @endif
            {{-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> --}}
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