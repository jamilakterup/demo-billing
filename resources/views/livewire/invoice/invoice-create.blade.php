<!-- Modal -->
<div class="modal fade" wire:ignore.self id="invoice-create-modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$isEdit?'Edit':'Create'}} Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form wire:submit.prevent="{{$isEdit?'invoiceUpdate':'invoiceStore'}}">
                <div class="modal-body">
                    <div class="border bg-light rounded p-4">

                        <div class="form-row">

                            <div class="form-group col-md-3">
                                <label for="inputEmail4">Customer</label>

                                <select wire:model.defer="state.customer_id" id="customer_id" class="tom-select">
                                    <option value="">--Please Select--</option>
                                    @foreach ($customers as $key=>$value)
                                    <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>

                                @if($errors->has('customer_id'))
                                <div class="text-danger text-sm"><small>{{ $errors->first('customer_id') }}</small>
                                </div>
                                @endif

                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputEmail4">Invoice Number</label>
                                <input type="text" wire:model.defer="state.number" class="form-control" id=""
                                    aria-describedby="emailHelp" placeholder="Enter invoice number" disabled>

                                @if($errors->has('number'))
                                <div class="text-danger text-sm"><small>{{ $errors->first('number') }}</small></div>
                                @endif
                            </div>

                            <div class="form-group col-md-3">
                                <label for="invoice_date">Invoice Date</label>
                                <input type="text" wire:model.defer="state.date" class="datetimepicker form-control"
                                    id="invoice_date">
                                @if($errors->has('date'))
                                <div class="text-danger text-sm"><small>{{ $errors->first('date') }}</small></div>
                                @endif
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputEmail4">Due Date</label>
                                <input type="date" wire:model.defer="state.due_date" class="datepicker form-control"
                                    id="" aria-describedby="emailHelp" placeholder="Enter due date">
                                @if($errors->has('due_date'))
                                <div class="text-danger text-sm"><small>{{ $errors->first('due_date') }}</small></div>
                                @endif
                            </div>

                        </div>

                        <div class="form-row">

                            <div class="form-group col-md-3">
                                <label>Invoice Type</label>

                                <select class="tom-select" wire:model.defer="state.invoice_type_id">
                                    <option value="">--Please Select--</option>
                                    @foreach($invoiceTypes as $invoiceType)
                                    <option value="{{$invoiceType->id}}">{{$invoiceType->invoice_type_name}}</option>
                                    @endforeach
                                </select>


                                @if($errors->has('invoice_type_id'))
                                <div class="text-danger text-sm"><small>{{ $errors->first('invoice_type_id') }}</small>
                                </div>
                                @endif
                            </div>

                            <div class="form-group col-md-3">
                                <label for="inputEmail4">Recurring</label>

                                <select wire:model.defer="state.type" id="type" class="form-control">
                                    <option value="">--Please Select--</option>
                                    <option value="1">Recurring</option>
                                    <option value="0">One time</option>
                                </select>

                                @if($errors->has('type'))
                                <div class="text-danger text-sm"><small>{{ $errors->first('type') }}</small></div>
                                @endif
                            </div>


                            <div class="col-md-6" id="interval-container"
                                style="{{$type==1?'display:block':'display:none'}}">
                                <div class="row">

                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Recurring interval</label>
                                        <select wire:model.defer="state.recurring_interval" id="interval"
                                            class="form-control @error('recurring_interval') is-invalid @enderror">
                                            <option value="">--Please Select--</option>
                                            <option value="30">Monthly</option>
                                            <option value="365">Yearly</option>
                                        </select>

                                        @error('recurring_interval')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="recurring_start_date">Recurring Start Date</label>
                                        <input type="date" wire:model.defer="state.recurring_start_date"
                                            class="datepicker form-control" id="recurring_start_date"
                                            placeholder="dd-mm-yyyy">
                                        @if($errors->has('recurring_start_date'))
                                        <div class="text-danger text-sm"><small>{{
                                                $errors->first('recurring_start_date') }}</small></div>
                                        @endif
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>



                    <hr>


                    <div class="row">
                        <div class="col-md-8">
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
                                    @foreach ($carts as $cart)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$cart->name}}</td>
                                        <td>{{$cart->attributes['unit_name']}}</td>
                                        <td>{{$cart->price}}</td>
                                        <td>{{$cart->quantity}}</td>
                                        <td>{{$cart->quantity*$cart->price}}</td>
                                        <td>
                                            <a class="btn btn-outline-danger btn-sm"
                                                wire:click.prevent="cartDelete({{$cart->id}})"><i
                                                    class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5" class="text-center">Total</td>
                                        <td><input type="text" value="{{$cartTotal}}" class="form-control total"
                                                disabled /></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-center">Discount</td>
                                        <td><input wire:model.defer="discount" type="text"
                                                class="form-control discount key_up"></td>
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
                                        <td><input wire:model.defer="vat" type="text" class="form-control vat key_up">
                                        </td>
                                        <td>
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input wire:model.defer="vat_percent" type="checkbox"
                                                        id="checkboxPrimary2" class="check vat_percent">
                                                    <label for="checkboxPrimary2">
                                                        %
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5" class="text-center">Tax</td>
                                        <td><input wire:model.defer="tax" type="text" class="form-control key_up tax ">
                                        </td>
                                        <td>
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <input wire:model.defer="tax_percent" type="checkbox"
                                                        id="checkboxPrimary3" class="check tax_percent">
                                                    <label for="checkboxPrimary3">
                                                        %
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td colspan="5" class="text-center">Payment</td>
                                        <td><input wire:model.defer="payment" type="text" class="form-control payment">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-center">Due</td>
                                        <td><input type="text" wire:model.defer="due" class="form-control due"
                                                disabled /></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-4">
                            <div class="border bg-light rounded p-4">
                                <div class="row g-1 mb-3">
                                    <label class="col-sm-4 col-form-label">Product <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select class="tom-select product_id" wire:model.defer="addpro.product_id"
                                            id="inputProduct">
                                            <option value="">-Please select item-</option>
                                            @foreach ($products as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>

                                        @if($errors->has('product_id'))
                                        <div class="text-danger text-sm"><small>{{ $errors->first('product_id')
                                                }}</small></div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row g-1 mb-3">
                                    <label class="col-sm-4 col-form-label">Unit <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <select class="tom-select product_id" wire:model.defer="addpro.unit"
                                            id="inputProduct">
                                            <option value="">-Please select unit-</option>
                                            @foreach ($units as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>

                                        @if($errors->has('unit'))
                                        <div class="text-danger text-sm"><small>{{ $errors->first('unit') }}</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row g-1 mb-3">
                                    <label class="col-sm-4 col-form-label">Price <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" wire:model.defer="addpro.price" class="form-control"
                                            placeholder="Enter price">
                                        @if($errors->has('price'))
                                        <div class="text-danger text-sm"><small>{{ $errors->first('price') }}</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row g-1 mb-3">
                                    <label class="col-sm-4 col-form-label">Quantity <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" wire:model.defer="addpro.quantity" class="form-control"
                                            placeholder="Enter price">
                                        @if($errors->has('quantity'))
                                        <div class="text-danger text-sm"><small>{{ $errors->first('quantity') }}</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row g-1 mb-3">
                                    <label class="col-sm-4 col-form-label">Quantity <span
                                            class="text-danger">*</span></label>
                                    <div class="col-sm-8">
                                        <input type="text" wire:model.defer="addpro.quantity" class="form-control"
                                            placeholder="Enter price">
                                        @if($errors->has('quantity'))
                                        <div class="text-danger text-sm"><small>{{ $errors->first('quantity') }}</small>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row g-1 mb-3">
                                    <div class="col-sm-4">

                                    </div>
                                    <div class="col-sm-8">
                                        <button type="button" wire:click="addProduct" wire:loading.attr="disabled"
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
                                    </div>
                                </div>



                            </div>
                        </div>

                    </div>



                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" wire:loading.class="disabled"
                        class="btn btn-primary">{{$isEdit?'Update':'Submit'}}</button>
                </div>
            </form>
        </div>
    </div>
</div>