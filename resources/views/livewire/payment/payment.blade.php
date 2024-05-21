<div>
    @include('livewire.payment.create-payment')
    @include('livewire.payment.payment-show')
    @include('livewire.payment.experience-certificate')
    @include('livewire.payment.vat-show')
    @include('livewire.payment.tax-show')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Payment</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Payment</li>
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
                                <div class="card-title">Payments</div>
                                <div class="card-tools">
                                    <a class="btn-sm btn-primary" href="#" wire:click.prevent="addNewPayment"><i
                                            class="far fa-plus-square"></i> Create Payment</a>
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

                                <table id="example1" class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>SL.</th>
                                            <th>Invoice Number</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th style="width:138px">Documents</th>
                                            <th style="width:165px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>INVOICE-{{$payment->invoice->number}}</td>
                                            <td>{{$payment->date}}</td>
                                            <td>{{$payment->invoice->customer->name}}</td>
                                            <td>{{$payment->amount}}</td>
                                            <td>
                                                <a title="Experience Certificate" class="btn btn-outline-primary btn-sm"
                                                    wire:click.prevent="experienceCertificate({{$payment}})"><i
                                                        class="fa-solid fa-certificate"></i></a>
                                                <a title="Vat" class="btn btn-outline-primary btn-sm"
                                                    wire:click.prevent="showVat({{$payment}})">Vat</a>
                                                <a title="Tax" class="btn btn-outline-primary btn-sm"
                                                    wire:click.prevent="showTax({{$payment}})">Tax</a>
                                            </td>
                                            <td>
                                                <a title="View" class="btn btn-outline-primary btn-sm fas fa-eye"
                                                    wire:click.prevent="showHistory({{$payment}})"></a>
                                                <a title="View" class="btn btn-outline-primary btn-sm"
                                                    wire:click.prevent="showPayment({{$payment}})"><i
                                                        class="fa-solid fa-print"></i></a>

                                                <a title="Edit" class="btn btn-outline-primary btn-sm"
                                                    wire:click.prevent="editPayment({{$payment}})"><i
                                                        class="fas fa-edit"></i></a>

                                                <a title="Delete"
                                                    class="position-relative z-10 btn btn-outline-danger btn-sm"
                                                    wire:click.prevent="delete({{$payment->id}})"><i
                                                        class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        {{-- {{route('category.delete',$category->id)}} --}}

                                    </tbody>
                                </table>
                                <div class="mt-2">{{$payments->links('livewire.custom-pagination')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


</div>
@push('scripts')
<script>
    window.addEventListener('payment-store',function(e){
            if(e.detail.type=='error')
            {

                Swal.fire(
                'Opps',
                e.detail.title,
                e.detail.type
                );
            }
            else
            {
                $('#payment-create-modal').modal('hide');
                Swal.fire({
                    toast: true,
                    icon: e.detail.type,
                    title: e.detail.title,
                    animation: false,
                    position: 'top-right',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

            }

    });


    window.addEventListener('add-product',function(e){

        Swal.fire(
        'Opps!',
        e.detail.title,
        e.detail.type
        );


    });


    window.addEventListener('show-modal',function(e){
        $('#payment-create-modal').modal('show');

        $(".select2").select2({
            theme: 'bootstrap4',
            dropdownParent: $("#payment-create-modal"),
        });
    });


    $(document).ready(function(){
        $(document).on('change','.select2',function(e){
            var pid=$(this).val();

            Livewire.emit('invoiceSelect',pid);
        });

        $(document).on('keyup','.discount',function(e){
            let checkBox=$('.percent').prop("checked");
            let discount= parseFloat($(this).val());
            let total=parseFloat($('.total').val())
            let payment=parseFloat($('.payment').val());
            if(checkBox){
                discount=(total*discount)/100;
            }
            let due=total-(discount+payment);
            $('.due').val(due);
            //Livewire.emit('grandTotalChange');
        });


        $(document).on('keyup','.payment',function(e){
            let checkBox=$('.percent').prop("checked");
            let discount= parseFloat($('.discount').val());
            let total=parseFloat($('.total').val())
            let payment=parseFloat($(this).val());
            if(checkBox){
                discount=(total*discount)/100;
            }
            let due=total-(discount+payment);
            $('.due').val(due);
            //Livewire.emit('grandTotalChange');
        });
    });


    window.addEventListener('show-payment',function(e){
        $('#show-payment-modal').modal('show');
    });
    
    window.addEventListener('show-payment-hiistory',function(e){
        $('#show-payment-modal').modal('show');
    });

    window.addEventListener('show-certificate',function(e){
        $('#show-certificate-modal').modal('show');
    });

    window.addEventListener('show-vat',function(e){
        $('#show-vat-modal').modal('show');
    });

    window.addEventListener('show-tax',function(e){
        $('#show-tax-modal').modal('show');
    });

</script>
@endpush