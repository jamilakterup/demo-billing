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
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Quotation</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Quotation</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    @livewire('estimate.estimate-create')
    <!-- /.content -->
</div>
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




@push('scripts')


<script>
    $(document).on('change','#type',function(e){
        var invoiceType=$(this).val();
        if(invoiceType==1){
            $('#interval-container').css('display','block');
        }
        else{
            $('#interval-container').css('display','none');
        }
    });
</script>


<script>
    $(function () {
        bsCustomFileInput.init();
        });

        $(document).ready(function(){

            $(document).on('change','#product',function(){
                $pro=$(this).val();
                if($pro){
                    $.ajax({
                    type: "GET",
                    url:"/price/change/"+$pro,
                    dataType: "json",
                    beforeSend: function() {
                    $('.loading-container').show();
                    $('#price').val('');
                    $('#price').removeAttr('placeholder');
                    $('#price').attr('disabled', 'disabled');
                    },
                    success: function(response) {
                        $('.loading-container').hide();
                        $('#price').val(response.price);
                        $('#quantity').val(1);
                        $('#price').prop('placeholder','Enter quantity');
                        $('#price').prop('disabled', false);
                    }
                    });
                }
                else{
                    $('#price').val('');
                }

            });

            function global_change(){
                let discount_check=$('.discountPercent').prop("checked");
                let tax_check=$('.tax-percent').prop("checked");
                let vat_check=$('.vat-percent').prop("checked");

                let discount= parseFloat($('#discount').val());
                let vat= parseFloat($('#vat').val());
                let tax= parseFloat($('#tax').val());

                if($.isNumeric(discount)==false){
                    discount=0;
                }
                if($.isNumeric(tax)==false){
                    tax=0;
                }
                if($.isNumeric(vat)==false){
                    vat=0;
                }

                $.ajax({
                    type: "GET",
                    url:"/global/item/change/",
                    dataType: "json",
                    data: {
                        discount_check: discount_check,
                        tax_check: tax_check,
                        vat_check: vat_check,
                        discount: discount,
                        vat: vat,
                        tax: tax
                    },

                    success: function(response) {
                        if(response.status==1){
                            $('#total').val(response.total);
                            //$('#tax').val(response.tax);
                            //$('#discount').val(response.discount);
                            $('#grandTotal').val(response.grandTotal)

                        }
                    }
                    });
            }


          $(document).on('click','#add-product-btn',function(){
              
              var vatTax=$('#vat_tax').is(':checked');
              
              var product=$('#product').val();
              var price=$('#price').val();
              var quantity=$('#quantity').val();
              $(document).find("div.show-error").text('');

            $.ajax({
              type: "GET",
              url:"/add/product/",
              dataType: "json",
              data: {
                product: product,
                price: price,
                vatTax: vatTax,
                quantity:quantity
              },
              beforeSend: function() {
                  $('.btn-icon').html('<span class="fas fa-spinner fa-pulse mr-2"></span>');
                  $('#add-product-btn').attr('disabled', 'disabled');
              },
              success: function(response) {
                  if(response.status==0){
                    $.each(response.errors,function(field_name,error){
                            $(document).find('[name='+field_name+']').parent().children().last().text(error);
                    });

                  }
                  if(response.status==2){
                    Swal.fire(
                    'Opps..',
                    response.title,
                    'error'
                    );
                  }

                  if(response.status==1){
                    $("#selected-product-container").html(response.html);
                    global_change();
                  }

                  //alert(response);
                  // $(".total-price").text(response.totalPrice);
                  // $('.update-qty-'+id).text(response.totalQty);
                  // $('.cart-update-btn-spin-'+id).html('<span class="fas fa-circle-notch"></span>');
                  // $(".chcekout-product-list").html(response.check_out_list);

                  //$(".total-item").text(response.totalQty);

                  $('.btn-icon').html('<i class="fa fa-plus mr-2"></i>');
                  $('#add-product-btn').removeAttr("disabled");
                  $('#product').val('');
                  $('#price').val('');
                  $('#quantity').val('');
              }
            });

          });





          function cartdelete(id){
            $.ajax({
                    type: "GET",
                    url:"/cart/item/delete/"+id,
                    dataType: "json",

                    success: function(response) {
                        if(response.status==1){
                            $('.row_'+response.delid).remove();
                        }
                    }
                    });

          }

          $(document).on('click','.cart-delete',function(e){
              e.preventDefault();
            var cart_id=$(this).attr('cartid');

            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if(result.isConfirmed) {

                //$('#destroy_form_'+delid).submit();
                //window.location.href=route;
                cartdelete(cart_id);
                global_change();
                // Swal.fire(
                // 'Deleted!',
                // 'Item has been deleted.',
                // 'success'
                // );
            }

            });

          });


          $(document).on('click','.check',function(){
            global_change();
          });

          $(document).on('keyup','.key_up',function(){
            global_change();
          });
        });
</script>


<script>
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
        if(result.isConfirmed) {
            Livewire.emit('removalId');
        }
    });
});


// delete alert
window.addEventListener('delete_confirm',function(e){
    Swal.fire({
        toast: true,
        icon: 'success',
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
});

// delete toast
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


// upload toast
window.addEventListener('message',function(e){
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
    

</script>

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
                        Livewire.emitTo('estimate.estimate-create','cartDelete',delid);
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


<script>
    $(function () {
      $("#example1").DataTable({
        "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });

  $(function () {
      $("#example2").DataTable({
        "lengthChange": false, "autoWidth": false,
        "columnDefs":[{"orderable": false, "targets": [2] }],
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });


    $(document).ready(function(){


            $(document).on('click','.delete',function(e){
            e.preventDefault();
            let delid = $(this).attr('delid');
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if(result.isConfirmed) {

                // Swal.fire(
                // 'Deleted!',
                // 'Your file has been deleted.',
                // 'success'
                // );
                $('#destroy_form_'+delid).submit();
                //window.location.href=route;
            }

            });

          });

        $(".select2").select2({
            theme: 'bootstrap4',
        });

        $('.date').daterangepicker({
            singleDatePicker: true,
            format: 'YYYY-MM-DD',
            minYear: 2011,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD',
            },
            calender_style: "picker_3"
        });


    });


function load_tomselect() {
    document.querySelectorAll(".tom-select").forEach(el => {
        if (el && el instanceof Element && el.tagName === "SELECT") {
            el.style.display = "";
            if (!el.hasAttribute("data-tomselect-initialized")) {
                let settings = { sortField: false };
                if (el.multiple) {
                    settings.plugins = ["remove_button", "clear_button"];
                } else {
                    settings.plugins = ["clear_button"];
                }
                if (el._tomselect) {
                    let selectedValue = el._tomselect.getValue();
                    el._tomselect.destroy();
                    el._tomselect = null;
                    el.value = selectedValue;
                }
                el._tomselect = new TomSelect(el, settings);
                el.setAttribute("data-tomselect-initialized", "true");
            }
        }
    });
}

load_tomselect();



document.addEventListener("DOMContentLoaded", () => {
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('message.processed', (message, component) => {
           load_tomselect();
        });
    }
});


</script>
@endpush
@endsection