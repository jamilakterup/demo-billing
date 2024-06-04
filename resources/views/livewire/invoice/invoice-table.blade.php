<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Invoice</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Invoice</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Invoice</div>
                            <div class="card-tools">
                                {{-- <a class="btn-sm btn-primary" href="#" wire:click.prevent="addNewInvoice"><i
                                        class="far fa-plus-square"></i> Create Invoice</a> --}}
                                <button class="btn btn-primary w-100 btn-sm" type="button"
                                    onclick='_openModal("Create new invoice", "invoice.invoice-new","[]","xl")'> <i
                                        class="far fa-plus-square"></i> Create Invoice</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="btn-group mb-2" role="group" aria-label="Basic example">
                                    <button wire:click.prevent='exportCSV' type="button"
                                        class="btn btn-secondary">Export CSV</button>
                                </div>
                                <div class="mb-2">
                                    <input type="text" wire:model="searchField" class="form-control w-100"
                                        placeholder="Search.." />
                                </div>
                            </div>

                            <div class="table-responsive">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>SL.</th>
                                            <th>Invoice No.</th>
                                            {{-- <th>Type</th> --}}
                                            <th>Date</th>
                                            <th>Recurring</th>
                                            <th>Interval</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            {{-- <th>Discount</th> --}}
                                            <th>Due</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($invoices as $index=>$invoice)
                                        <tr>
                                            <td>{{$invoices->firstItem()+$index}}</td>
                                            <td>INVOICE-{{$invoice->number}}</td>
                                            {{-- <td>{{$invoice->invoice_type->invoice_type_name}}</td> --}}
                                            <td>{{$invoice->date}}</td>
                                            <td>{{$invoice->is_recurring?'Yes':'No'}}</td>
                                            <td>{{$invoice->recurring_interval}}</td>
                                            <td title="{{$invoice->customer->name}}">
                                                {{\Str::limit($invoice->customer->name, 10) }}</td>
                                            <td>
                                                @if($invoice->status)
                                                <span class="badge bg-success">PAID</span>
                                                @else
                                                <span class="badge bg-danger">UNPAID</span>
                                                @endif
                                            </td>
                                            <td>{{$invoice->total}}</td>
                                            {{-- <td>{{$invoice->discount}}</td> --}}
                                            <td>{{\App\Models\Invoice::get_due($invoice->id)}}</td>


                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <a class="btn btn-outline-primary btn-sm"
                                                        wire:click.prevent="print({{$invoice->id}})"
                                                        data="{{$invoice->file}}"><i class="fa-solid fa-print"></i></a>

                                                    <a class="btn btn-outline-info btn-sm mx-1"
                                                        href="{{route('invoice.show',$invoice->id)}}"><i
                                                            class="fas fa-eye"></i></a>

                                                    <a class="btn btn-outline-primary btn-sm fas fa-edit mx-1"
                                                        onclick='_openModal("Edit invoice", "invoice.invoice-edit",{{ json_encode(["id" => $invoice->id]) }},"xl")'></a>


                                                    <a class="btn btn-outline-danger btn-sm"
                                                        wire:click.prevent="delete({{$invoice->id}})"><i
                                                            class="fas fa-trash"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        {{-- {{route('category.delete',$category->id)}} --}}

                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2">{{$invoices->links('livewire.custom-pagination')}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->