@extends('layouts.master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Quotation</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Quotation</li>
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

          {{Form::model($agreement,['route'=>['agreement.update',$agreement->id],'method'=>'PUT', 'files'=>true])}}

          <div class="card">
            <div class="card-header">
              <div class="card-title">Edit quotation</div>
              <div class="card-tools">
                <a class="btn-sm btn-primary" href="{{url()->previous()}}"><i class="fas fa-arrow-circle-left"></i>
                  Back</a>
              </div>
            </div>
            <div class="card-body">
              <div class="bg-light p-3 border rounded mb-3">
                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="inputEmail4">Customer</label>
                    {{Form::select('customer_id',$customers,null,['class'=>'form-control select2','id'=>''])}}
                    <div class="text-danger">{{$errors->first('customer_id')}}</div>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="inputDname4">Quotation Number</label>
                    {{Form::text('agreement_number',$counter->number,['class'=>'form-control','placeholder'=>'agreement
                    Number','readonly'=>'readonly'])}}
                    <div class="text-danger">{{$errors->first('agreement_number')}}</div>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="inputDname4">Quotation Type</label>
                    {{Form::select('quotation_type_id',$quotationTypes,null,['class'=>'form-control'])}}
                    <div class="text-danger">{{$errors->first('quotation_type_id')}}</div>
                  </div>



                </div>

                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label for="inputEmail4">Quotation Date</label>
                    {{Form::text('agreement_date',null,['class'=>'form-control date','placeholder'=>'agreement
                    Date','id'=>''])}}
                    <div class="text-danger">{{$errors->first('agreement_date')}}</div>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="inputDname4">Expiry Date</label>
                    {{Form::text('expiry_date',null,['class'=>'form-control date','placeholder'=>'Display
                    name','id'=>''])}}
                    <div class="text-danger">{{$errors->first('expiry_date')}}</div>
                  </div>

                  <div class="form-group col-md-4">
                    <label for="inputDname4">Reference Number</label>
                    {{Form::text('reference_number',null,['class'=>'form-control','placeholder'=>'Reference
                    Number','id'=>''])}}
                    <div class="text-danger">{{$errors->first('reference_number')}}</div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-9">
                  <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                      <thead>
                        <tr>
                          <th style="width:10%">SL</th>
                          <th style="width:30%">Product</th>
                          <th style="width:10%">Unit</th>
                          <th style="width:10%; text-align:right;">Price</th>
                          <th style="width:14%; text-align:right;">Qty</th>
                          <th style="width:20%; text-align:right;">Total</th>
                          <th style="width:6%">Action</th>
                        </tr>
                      </thead>
                      <tbody id="selected-product-container">
                        @if(\Cart::getContent()->count()>0)



                        @foreach (\Cart::getContent() as $cart)
                        <tr class="row_{{$cart->id}}">
                          <td>{{$loop->iteration}}</td>
                          <td>{{$cart->name}}</td>
                          <td>{{$cart->attributes['unit_name']}}</td>
                          <td style="text-align:right">{{$cart->price}}</td>
                          <td style="text-align:right">{{$cart->quantity}}</td>
                          <td style="text-align:right">{{$cart->quantity*$cart->price}}</td>
                          <td>
                            <a href="#" class="btn btn-sm btn-danger cart-delete" cartid="{{$cart->id}}"><i
                                class="fa fa-trash-alt"></i></a>
                          </td>
                        </tr>
                        @endforeach
                        @endif
                      </tbody>
                    </table>

                    <table class="table table-borderless border-top">
                      <tbody>
                        <tr>
                          <td style="width: 68%" align="right"><b>Total</b></td>
                          <td>
                            {{Form::text('sub_total',\Cart::getTotal(),['class'=>'form-control','id'=>'total','readonly'=>true])}}
                          </td>
                        </tr>

                        <tr>
                          <td style="width: 68%" align="right"><b>Discount</b></td>
                          <td>
                            {{Form::text('discount',$agreement->discount,['class'=>'form-control
                            key_up','id'=>'discount'])}}
                          </td>

                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-primary d-inline">
                                <input type="checkbox" name="discount_percent" id="checkboxPrimary1"
                                  class="discountPercent check">
                                <label for="checkboxPrimary1">
                                  %
                                </label>
                              </div>
                            </div>
                          </td>

                        </tr>


                        <tr>
                          <td style="width: 68%" align="right"><b>Vat</b></td>
                          <td>
                            {{Form::text('vat',$agreement->vat,['class'=>'form-control key_up','id'=>'vat'])}}
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-primary d-inline">
                                <input type="checkbox" name="vat_percent" id="checkboxPrimary3"
                                  class="vat-percent check">
                                <label for="checkboxPrimary3">
                                  %
                                </label>
                              </div>
                            </div>
                          </td>
                        </tr>


                        <tr>
                          <td style="width: 68%" align="right"><b>Tax</b></td>
                          <td>
                            {{Form::text('tax',$agreement->tax,['class'=>'form-control key_up','id'=>'tax'])}}
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-primary d-inline">
                                <input type="checkbox" name="tax_percent" id="checkboxPrimary2"
                                  class="tax-percent check">
                                <label for="checkboxPrimary2">
                                  %
                                </label>
                              </div>
                            </div>
                          </td>
                        </tr>

                        <tr class="border-top">
                          <td align="right"><b>Grand Total</b></td>
                          <td>
                            {{Form::text('grand_total',$agreement->total,['class'=>'form-control','id'=>'grandTotal','readonly'=>true])}}
                          </td>
                          <td>

                          </td>
                        </tr>

                      </tbody>
                    </table>

                    <div>
                      <div class="form-group">
                        <label for="inputEmail4">Note</label>
                        {{Form::textarea('note',null,['class'=>'form-control','rows'=>'2'])}}
                        <div class="text-danger">{{$errors->first('note')}}</div>
                      </div>
                    </div>


                    <table class="table table-borderless">
                      <tbody>
                        <tr>
                          <td>
                            <label for="work_order" class="form-label">Work Order <span
                                class="text-sm">(Optional)</span></label>

                            {{Form::file('work_order',['class'=>'form-control'])}}

                            <div class="text-danger">{{$errors->first('work_order')}}
                            </div>
                          </td>
                          <td>
                            <label for="work_completion" class="form-label">Work
                              Completion
                              Certificate <span class="text-sm">(Optional)</span></label>

                            {{Form::file('work_completion',['class'=>'form-control'])}}

                            <div class="text-danger">{{$errors->first('work_completion')}}
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>


                </div>

                <div class="col-md-3">
                  <div class="bg-light p-3 border rounded">
                    <div class="form-group">
                      <label for="inputEmail4">Product</label>
                      {{Form::select('product',$products,null,['class'=>'form-control product-change
                      select2','id'=>'product'])}}
                      <div class="text-danger show-error">{{$errors->first('product')}}</div>
                    </div>



                    <div class="form-group">
                      <label for="inputDname4">Price</label>
                      <div style="position: relative">
                        <div class="loading-container"
                          style="position: absolute; top:50%; left:10px; transform: translateY(-50%); display:none">
                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                          Loading...
                        </div>
                        {{Form::text('price',null,['class'=>'form-control','placeholder'=>'Enter
                        price','id'=>'price'])}}
                        <div class="text-danger show-error">{{$errors->first('price')}}</div>
                      </div>
                    </div>
                    <div class="form-group">
                      <input type="checkbox" id="vat_tax">
                      <label for="vat_tax">Include Vat and Tax?</label>
                    </div>
                    <div class="form-group">
                      <label for="inputDname4">Quantity</label>
                      {{Form::text('quantity',null,['class'=>'form-control','placeholder'=>'Enter
                      quantity','id'=>'quantity'])}}
                      <div class="text-danger show-error">{{$errors->first('quantity')}}</div>
                    </div>

                    <div class="d-flex justify-content-center">
                      <button class="btn btn-secondary border" type="button" id="add-product-btn"><span
                          class="btn-icon"><i class="fa fa-plus mr-2"></i></span>ADD PRODUCT</button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="card-footer">
              <div class="d-flex justify-content-center">
                {{Form::submit('Update',['class'=>'btn btn-primary text-uppercase font-weight-bold'])}}
              </div>
            </div>
          </div>
          {{Form::close()}}
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->




@push('scripts')

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


        // function cartUpdate(id,value){
        // $.ajax({
        //     type: "GET",
        //     url:"/cart/update/",
        //     dataType: "json",
        //     data: {
        //         id: id,
        //         value:value
        //     },
        //     beforeSend: function() {
        //         //$("#loading-image").show();
        //         $('.cart-update-btn-spin-'+id).html('<span class="fas fa-spinner fa-pulse"></span>');
        //     },
        //     success: function(response) {
        //         $(".total-price").text(response.totalPrice);
        //         $('.update-qty-'+id).text(response.totalQty);
        //         $('.cart-update-btn-spin-'+id).html('<span class="fas fa-circle-notch"></span>');
        //         $(".chcekout-product-list").html(response.check_out_list);

        //         //$(".total-item").text(response.totalQty);
        //     }
        //   });
        //}
</script>
@endpush


@endsection