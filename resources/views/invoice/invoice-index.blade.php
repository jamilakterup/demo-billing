@extends('layouts.master')
@section('style')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"
    integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/css/tom-select.bootstrap4.min.css"
    integrity="sha512-rSpBVO3jAoJ/9Mqqk9gjVGgZX5ZFiwYXap9xWfweRUoLdSgp8NJ6ERvFc0jW+VsaVLQY4QJts1MF9TQxiP8IEA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{asset('plugins/printjs/print.min.css')}}" rel="stylesheet" type="text/css">
@livewireStyles
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
@livewire('invoice.invoice-table')
<!-- /.content-wrapper -->

@endsection

@section('js')
@livewireScripts
<x-livewiremodal-base />
<!-- Daterange picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
    integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/js/tom-select.complete.js"
    integrity="sha512-KfTOBVJv8qnV1b+2tsbTLepS7+RAgmVV0Odk6cj1eHxbR8WFX99gwIWOutwFAUlsve3FaGG3VxoPWWLRnehX1w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script src="{{asset('plugins/printjs/print.min.js')}}"></script>

@endsection

@push('scripts')
<script>
    window.addEventListener('invoice-store',function(e){
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
            $('#x-modal').modal('hide');
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
    window.addEventListener('is_delete_confirm',function(event){

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
        if(result.value) {
            Livewire.emitTo('invoice.invoice-table','deleteConfirmed');
        }

        });
    });

    window.addEventListener('notification',function(e){
        Swal.fire({
            toast: true,
            icon: e.detail.type,
            title: e.detail.msg,
            animation: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    });


    window.addEventListener('invoice-preview',function(e){
        var invoiceName=e.detail.invoiceName;
        var path='{{asset('/pdf/')}}'+'/'+invoiceName;

        Swal.fire({
        title: '<strong>Preview</strong>',
        width:'800px',
        html:
            '<iframe src="'+path+'" width="100%" height="500px"></iframe>',
        showCloseButton: true,
        showCancelButton: true,
        })
    });

    window.addEventListener('print',function(e){
        const timestamp = new Date().getTime();
        var invoiceName=e.detail.file;
        var path='{{asset('/pdf/')}}'+'/'+invoiceName+'?t='+timestamp;

        printJS({
            printable: path,
            showModal:true,
            modalMessage:'Retrieving Document...',
            type: 'pdf',
        })
    });

    

    $(document).on("click",".del",function(){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if(result.value) {
                    var delid=$(this).attr("data");

                    var type=$(this).attr("type");
                    if(type=="new"){
                        Livewire.emitTo('invoice.invoice-new','cartDelete',delid);
                    }
                    else{
                        Livewire.emitTo('invoice.invoice-edit','cartDelete',delid);
                    }
                }
        });
    });

    $(document).on("click","#allClear",function(){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if(result.value) {
                    Livewire.emitTo('purchase.rawmaterial-purchase-new','deleteAll');
                }

        });
    });


    window.addEventListener('show-modal',function(e){
        $('#invoice-create-modal').modal('show');

        $(".select2").select2({
            theme: 'bootstrap4',
            dropdownParent: $("#invoice-create-modal"),
        });
    });

    window.addEventListener('select-reload',function(event){
        $(".select2").select2({
            theme: 'bootstrap4',
            dropdownParent: $(".modal"),
        });
    });

    
    $(document).on('change','#type',function(e){
        var invoiceType=$(this).val();
        if(invoiceType==1){
            $('#interval-container').css('display','block');
        }
        else{
            $('#interval-container').css('display','none');
        }
    });


    // vat tax counter::
    $(document).ready(function(){

        $(document).on('keyup','.key_up',function(e){
            calculateTotal();
        });

        $(document).on('keyup','.payment',function(e){
            calculateTotal();
        });

        $(document).on('click','.check',function(){
            calculateTotal();
        });

        // $(document).on('change','.include-vat-tax',function(){
        //     let includingVatTax= $(this).val();
        //     console.log(includingVatTax)
        // });

        // $(document).on('keyup','.vatTaxVal',function(){
        //     console.log($('.vatTaxVal').val(),$('.vatTax_parcentage').prop('checked'))
        // })


        function calculateTotal() {
            let discount_percent = $('.discount_percent').prop("checked");
            let vat_percent = $('.vat_percent').prop("checked");
            let tax_percent = $('.tax_percent').prop("checked");
            let vatTax_parcentage= $('.vatTax_parcentage').prop('checked');

            let discount = parseFloat($('.discount').val()) || 0;
            let vat = parseFloat($('.vat').val()) || 0;
            let tax = parseFloat($('.tax').val()) || 0;
            let vatTaxVal= $('.vatTaxVal').val() || 0;

            let payment = parseFloat($('.payment').val()) || 0;
            let total= parseFloat($('.total').val()) || 0;

            
            // if(vatTax_parcentage==true){
            //     total= (total*100/(100-vatTaxVal))
            // }else{
            //     total= parseFloat($('.total').val()) || 0;
            // }
            // console.log(total)

            // Calculate discount based on checkbox state
            if (discount_percent==true) {
                discount = (total * discount) / 100;
            }else{
                discount = parseFloat($('.discount').val()) || 0;
            }

            // Calculate totalWith using the formula
            if(vat_percent==true || tax_percent==true){
                var totalWith = (total * 100) / (100 - (vat + tax));
            }else{
                var totalWith=total+tax+vat;
            }

            // console.log(totalWith,vat_percent,tax_percent,discount_percent,discount)
            let due = totalWith - (discount + payment);
            $('.due').val(due);
        }

    });




    document.addEventListener("livewire:load", function (event) {
        window.livewire.hook('message.processed', () => {
            
            $(document).ready(function() {
                $('.tom-select').each(function() {
                    new TomSelect(this);
                });                
            });
            
            
            jQuery('.date').datetimepicker({
                format:'Y-m-d',
                timepicker:false,
                formatDate:'Y-m-d',
            });

        })
    });


    


</script>
@endpush