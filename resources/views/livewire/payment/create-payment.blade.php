<!-- Modal -->
<div class="modal fade" wire:ignore.self id="payment-create-modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{$isEdit?'Edit':'Create'}} Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form wire:submit.prevent="{{$isEdit?'paymentUpdate':'paymentStore'}}">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" wire:ignore>
                                    <label for="inputEmail4">Invoice</label>
                                    <select class="select2 form-control @error('invoice_id') is-invalid @enderror">
                                        <option value="">--Please Select--</option>
                                        @foreach ($invoices as $invoice)
                                        <option value="{{$invoice->id}}">
                                            {{$invoice->number}}-{{$invoice->customer->name}}</option>
                                        @endforeach
                                    </select>

                                    @error('invoice_id')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail4">Payment Type</label>
                                    <select wire:model.defer="state.payment_type_id"
                                        class="form-control @error('payment_type_id') is-invalid @enderror">
                                        <option value="">--Please Select--</option>
                                        @foreach ($payment_types as $payment_type)
                                        <option value="{{$payment_type->id}}">{{$payment_type->name}}</option>
                                        @endforeach
                                    </select>

                                    @error('payment_type_id')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail4">Date</label>
                                    <input type="date" wire:model.defer="state.date"
                                        class="form-control @error('date') is-invalid @enderror"
                                        aria-describedby="emailHelp" placeholder="Enter date">
                                    @error('date')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div class="form-group">
                                        <label for="inputEmail4">Net Amount</label>

                                        <input style="width:250px" type="text" wire:model="state.net_amount"
                                            class="form-control @error('net_amount') is-invalid @enderror" id=""
                                            aria-describedby="emailHelp" placeholder="Enter net_amount">

                                        @error('net_amount')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail4">Vat & Tax</label>

                                        <input style="width:250px" type="text" wire:model="state.vat_tax"
                                            class="form-control @error('vat_tax') is-invalid @enderror" id=""
                                            aria-describedby="emailHelp" placeholder="Enter vat_tax">

                                        @error('vat_tax')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="form-group">
                                        <label for="inputEmail4">commission</label>

                                        <input style="width:250px" type="text" wire:model="state.commission"
                                            class="form-control @error('commission') is-invalid @enderror" id=""
                                            aria-describedby="emailHelp" placeholder="Enter commission">

                                        @error('commission')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail4">Cost</label>

                                        <input style="width:250px" type="text" wire:model="state.cost"
                                            class="form-control @error('cost') is-invalid @enderror" id=""
                                            aria-describedby="emailHelp" placeholder="Enter cost">

                                        @error('cost')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail4">Total Amount</label>

                                    <input type="text" wire:model="state.amount"
                                        class="form-control @error('amount') is-invalid @enderror" id=""
                                        aria-describedby="emailHelp" placeholder="Enter amount" disabled>

                                    @error('amount')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <label for="inputEmail4">Comment</label>

                                    <textarea wire:model.defer="state.comment"
                                        class="form-control @error('comment') is-invalid @enderror"
                                        aria-describedby="emailHelp" placeholder="Enter comment"></textarea>

                                    @error('comment')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                {{--
                                <div class="form-group">
                                    <label for="upload_file">Upload File</label>

                                    <input wire:model.defer="state.upload_file" type="file"
                                        class="form-control @error('upload_file') is-invalid @enderror" id="upload_file"
                                        aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                                    @error('upload_file')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div> --}}
                                <div class="form-group">
                                    <label for="certificate_file">Upload Experience Certificate</label>

                                    <input wire:model.defer="state.certificate_file" type="file"
                                        class="form-control @error('certificate_file') is-invalid @enderror"
                                        id="certificate_file" aria-describedby="inputGroupFileAddon04"
                                        aria-label="Upload">
                                    @error('certificate_file')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="d-flex">
                                    <div class="form-group mr-3">
                                        <label for="vat_file">Upload Vat File</label>

                                        <input wire:model.defer="state.vat_file" type="file"
                                            class="form-control @error('vat_file') is-invalid @enderror" id="vat_file"
                                            aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                                        @error('vat_file')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="tax_file">Upload Tax File</label>

                                        <input wire:model.defer="state.tax_file" type="file"
                                            class="form-control @error('tax_file') is-invalid @enderror" id="tax_file"
                                            aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                                        @error('tax_file')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>


                            </div>

                            <div class="col-md-6">
                                <p class="text-center mb-2">Payment Details</p>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Invoice Number</td>
                                        <td>{{$showInvoice!=''?$showInvoice->number:''}}</td>
                                    </tr>
                                    <tr>
                                        <td>Customer Name</td>
                                        <td>{{$showInvoice!=''?$showInvoice->customer->name:''}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Amount</td>
                                        <td>{{$showInvoice!=''?$showInvoice->sub_total:''}}</td>
                                    </tr>
                                    <tr>
                                        <td>Discount</td>
                                        <td>{{$showInvoice!=''?$showInvoice->discount:''}}</td>
                                    </tr>
                                    <tr>
                                        <td>Vat</td>
                                        <td>{{$showInvoice!=''?$showInvoice->vat:''}}</td>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <td>{{$showInvoice!=''?$showInvoice->tax:''}}</td>
                                    </tr>
                                    <tr>
                                        <td>Grand Total</td>
                                        <td>{{$showInvoice!=''?$showInvoice->total:''}}</td>
                                    </tr>
                                </table>
                                <?php
                            $totalPayment=0;
                            ?>
                                <table class="table table-bordered">
                                    <thead class="bg-light">

                                        <tr>
                                            <th>SL.</th>
                                            <th>Date</th>
                                            <th>Payment Type</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($showPayments as $showPayment)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$showPayment->date}}</td>
                                            <td>{{$showPayment->payment_type->name}}</td>
                                            <td>{{$showPayment->amount}}</td>
                                            <?php
                                        $totalPayment+=$showPayment->amount;
                                        ?>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-center">Total Payment</td>
                                            <td>{{$totalPayment}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-center">Due</td>
                                            <td>{{$showInvoice!=''?$showInvoice->total-$totalPayment:''}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{$isEdit?'Update':'Submit'}}</button>
                </div>
            </form>
        </div>
    </div>
</div>