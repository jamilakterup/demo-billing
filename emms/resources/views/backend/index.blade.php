
@extends('backend.layout')
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->

    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
               
            </div>
            </div>
        </div>
    </div>
    

        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user-plus"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total User</span>
                        <span class="info-box-number">{{$totalUser}}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Member</span>
                        <span class="info-box-number">{{$totalMember}}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-money-check-alt"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Running loan</span>
                        <span class="info-box-number">{{$runningLoan}}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-square"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Completed loan</span>
                        <span class="info-box-number">{{$completeLoan}}</span>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Summary (Today)</h3>
{{--                        <div class="card-tools">--}}
{{--                            <a href="#" class="btn btn-tool btn-sm">--}}
{{--                                <i class="fas fa-download"></i>--}}
{{--                            </a>--}}
{{--                            <a href="#" class="btn btn-tool btn-sm">--}}
{{--                                <i class="fas fa-bars"></i>--}}
{{--                            </a>--}}
{{--                        </div>--}}
                    </div>
                    <div class="card-body p-0">

                    <table class="table table-striped table-valign-middle table-responsive-sm">
                        <tbody>
                            <tr>
                                <th>Receivable amount</th>
                                <td>{{$receivable_amount}}</td>
                                <td><button class="btn-sm btn-primary" type="button"  id="receivable_amount"><i class="fas fa-info-circle"></i></button></td>
                            </tr>
                            <tr>
                                <th>Received amount</th>
                                <td>{{$received_amount}}</td>
                                <td><button class="btn-sm btn-primary" type="button"  id="received_amount"><i class="fas fa-info-circle"></i></button></td>

                            </tr>
                            <tr>
                                <th>Pending amount</th>
                                <td></td>
                                <td><button class="btn-sm btn-primary" type="button"  id="pending_amount"><i class="fas fa-info-circle"></i></button></td>
                            </tr>

                            <tr>
                                <th>Total savings</th>
                                <td>{{$total_savings}}</td>
                                <td><button class="btn-sm btn-primary" type="button"  id="total_savings"><i class="fas fa-info-circle"></i></button></td>
                            </tr>
                            <tr>
                                <th>Total withdrawal</th>
                                <td>{{$total_withdrawal}}</td>
                                <td><button class="btn-sm btn-primary" type="button"  id="total_withdrawal"><i class="fas fa-info-circle"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                       
                    </div>
                </div>
            </div>
        </div>


        

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Receivable Loan (Today)</h3>
{{--                        <div class="card-tools">--}}
{{--                            <a href="#" class="btn btn-tool btn-sm">--}}
{{--                                <i class="fas fa-download"></i>--}}
{{--                            </a>--}}
{{--                            <a href="#" class="btn btn-tool btn-sm">--}}
{{--                                <i class="fas fa-bars"></i>--}}
{{--                            </a>--}}
{{--                        </div>--}}
                    </div>
                    <div class="card-body p-0">
                        @php
                        $i=1;
                        @endphp
                        <table class="table table-striped table-valign-middle table-responsive-sm">
                            <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Loan ID</th>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentDates as $paymentDate)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$paymentDate->loan_id}}</td>
                                    <td>{{$paymentDate->member->memberName->name}}</td>
                                    <td>{{$paymentDate->amount}}</td>
                                    <td>{{$paymentDate->payment_date}}</td>
                                    <td>
                                        <a href="{{url('instalment/create2',$paymentDate->loan_id)}}" class="btn-sm btn-primary"><i class="fa fa-dollar-sign"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!--/. container-fluid -->
</section>


@push('scripts')
       <script>
           $('#datetime').datetimepicker({

           });


           ///receivable amount by users on current date
           ///user controller

           $(document).ready(function(){
               $("#receivable_amount").click(function (){
                   $.ajax({
                       type:'GET',
                       url:'{{url('/receivable/amount/user')}}',
                       data:  {},
                       success:function(response)
                       {
                           $("#exampleModalLong").modal('show');
                           $('#exampleModalLongTitle').text('Receivable Amount By Users');

                           $('.modal-body').html(response);
                       },
                       error: function(){
                         alert("failure From php side!!! ");
                         }
                   });
               }); 
           });

            ///received amount by users on current date
            ///user controller
           $(document).ready(function(){
               $("#received_amount").click(function (){
                   $.ajax({
                       type:'GET',
                       url:'{{url('/received/amount/user')}}',
                       data:  {},
                       success:function(response)
                       {
                           $("#exampleModalLong").modal('show');
                           $('#exampleModalLongTitle').text('Received Amount By Users');

                           $('.modal-body').html(response);
                       },
                       error: function(){
                         alert("failure From php side!!! ");
                         }
                   });
               }); 
           });

           ///Total savings by users on current date
           ///user controller

           $(document).ready(function(){
               $("#total_savings").click(function (){
                   $.ajax({
                       type:'GET',
                       url:'{{url('/total/savings/amount/user')}}',
                       data:  {},
                       success:function(response)
                       {
                           $("#exampleModalLong").modal('show');
                           $('#exampleModalLongTitle').text('All saving By Users');

                           $('.modal-body').html(response);
                       },
                       error: function(){
                         alert("failure From php side!!! ");
                         }
                   });
               }); 
           });


           ///Total withdrawal by users on current date
           ///user controller

           $(document).ready(function(){
               $("#total_withdrawal").click(function (){
                   $.ajax({
                       type:'GET',
                       url:'{{url('/total/withdrawal/amount/user')}}',
                       data:  {},
                       success:function(response)
                       {
                           $("#exampleModalLong").modal('show');
                           $('#exampleModalLongTitle').text('All withdrawal By Users');

                           $('.modal-body').html(response);
                       },
                       error: function(){
                         alert("failure From php side!!! ");
                         }
                   });
               }); 
           });


       </script>
    @endpush

@stop