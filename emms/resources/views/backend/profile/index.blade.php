
@extends('backend.profile_layout')
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
            <div class="col-12 col-sm-6 col-md-6">
                <div class="card">
                        <div class="card-header">
                            <div class="card-title">User Details (all)</div>
                        </div>
                        <div class="card-body">
                        
                            <table class="table table-striped table-valign-middle table-responsive-sm">
                                
                                <tbody>
                                    <tr>
                                        <th>Total Member</th>
                                        <td>{{$totalMember}}</td>
                                        <td><a class="btn-sm btn-primary" href="#all_member"><i class="fa fa-angle-down"></i></a></td>
                                    </tr>
                                    <tr>
                                        <th>Total loan</th>
                                        <td>{{$totalLoan}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Running loan</th>
                                        <td>{{$runningLoan}}</td>
                                        <td><a class="btn-sm btn-primary" href="#running_loan"><i class="fa fa-angle-down"></i></a></td>
                                    </tr>
                                    <tr>
                                        <th>Completed loan</th>
                                        <td>{{$completeLoan}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Total principal</th>
                                        <td>{{$totalPrincipal}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Total Interest</th>
                                        <td>{{$totalInterest}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Received amount</th>
                                        <td>{{$totalReceived}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Total pending</th>
                                        <td>{{($totalPrincipal+$totalInterest)-$totalReceived}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Total savings</th>
                                        <td>{{$totalSavings}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Total withdrawal</th>
                                        <td>{{$totalWithdrawal}}</td>
                                        <td></td>
                                    </tr>
                                
                                </tbody>
                            </table>
                        </div>

                </div>
            </div>


            <div class="col-12 col-sm-6 col-md-6">
                <div class="card">
                        <div class="card-header">
                            <div class="card-title">User Details (current date)</div>
                        </div>
                        <div class="card-body">
                        
                            <table class="table table-striped table-valign-middle table-responsive-sm">
                                
                                <tbody>
                                    <tr>
                                        <th>Receivable amount</th>
                                        <td>{{$receivable_current_date}}</td>
                                        <td><button type="button" user_id="{{$user->id}}" id="receivable_amount_member" class="btn-sm btn-primary"><i class="fa fa-info-circle"></i></button></td>
                                    </tr>
                                    <tr>
                                        <th>Received amount</th>
                                        <td>{{$received_current_date}}</td>
                                        <td><button type="button" user_id="{{$user->id}}" id="received_amount_member" class="btn-sm btn-primary"><i class="fa fa-info-circle"></i></button></td>
                                    </tr>
                                    <tr>
                                        <th>Pending amount</th>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Total savings</th>
                                        <td>{{$savings_current_date}}</td>
                                        <td><button type="button" user_id="{{$user->id}}" id="savings_amount_member" class="btn-sm btn-primary"><i class="fa fa-info-circle"></i></button></td>
                                    </tr>
                                    <tr>
                                        <th>Total withdrawal</th>
                                        <td>{{$withdrawal_current_date}}</td>
                                        <td><button type="button" user_id="{{$user->id}}" id="withdrawal_amount_member" class="btn-sm btn-primary"><i class="fa fa-info-circle"></i></button></td>
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
                                        <a href="{{url('profile/instalment/create2',$paymentDate->loan_id)}}" class="btn-sm btn-primary"><i class="fa fa-dollar-sign"></i></a>
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

        {{-- running loan --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="running_loan">
                        <div class="card-header">
                            <div class="card-title">Running Loan</div>
                            {{-- <div class="card-tools">
                                <a  href="{{url()->previous()}}" class="btn btn-tool" data-card-widge="collapse">
                                    <i class="fas fa-backward"></i>
                                </a>
                            </div> --}}
                        </div>
                        <div class="card-body">
                         @php
                                $i=1;
                            @endphp

                            <table class="table table-striped table-valign-middle table-responsive-sm">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Loan ID</th>
                                    <th>Member</th>
                                    <th>Principal</th>
                                    <th>Interval</th>
                                    <th>Instalment</th>
                                    <th>Paid</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($loans as $loan)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$loan->id}}</td>


                                        <td><img src="{{asset('img/members/'.$loan->memberName->photo)}}" class="img-circle img-size-32 mr-2">{{$loan->memberName->name}}</td>
                                        <td>{{$loan->principal}}</td>
                                        <td>{{$loan->inst_type->name}}</td>
                                        <td>{{$loan->number_of_instalment}}</td>
                                        <td>{{\App\Helper::instalment_count($loan->id)}}</td>
                                        <td>
                                            @if($loan->paid==0)
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-success">Completed</span>
                                            @endif

                                        </td>

                                        <td>
                                            @if($loan->paid==0)
                                            <a href="{{url('profile/instalment/create2',$loan->id)}}" title="Instalment" class="btn-sm btn-primary"><i class="fa fa-dollar-sign"></i></a>
                                            @else
                                                <a href="javascript:void(0)" title="Instalment" class="btn-sm btn-secondary disabled"><i class="fa fa-dollar-sign"></i></a>
                                            @endif
                                            <a href="{{route('profileLoan.show',$loan->id)}}" title="show details" class="btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                            <a href="{{route('profileLoan.edit',$loan->id)}}" title="edit" class="btn-sm btn-success "><i class="fas fa-edit"></i></a>
                                            <a href="" title="delete" class="btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                           
                        </div>

                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>


    </div><!--/. container-fluid -->
</section>

@push('scripts')
       <script>
           $('#datetime').datetimepicker({

           });


           ///receivable amount by users on current date
           ///user controller

           $(document).ready(function(){
               $("#receivable_amount_member").click(function (){

                   var user_id=$(this).attr('user_id');
                   $.ajax({
                       type:'GET',
                       url:'{{url('/receivable/amount/member')}}',
                       data:  {user_id:user_id},
                       success:function(response)
                       {
                           
                           $("#exampleModalLong").modal('show');
                           $('#exampleModalLongTitle').text('Receivable Amount');

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
               $("#received_amount_member").click(function (){
                    var user_id=$(this).attr('user_id');
                   $.ajax({
                       type:'GET',
                       url:'{{url('/received/amount/member')}}',
                       data:  {user_id:user_id},
                       success:function(response)
                       {

                           $("#exampleModalLong").modal('show');
                           $('#exampleModalLongTitle').text('Received Amount');

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
               $("#savings_amount_member").click(function (){
                    var user_id=$(this).attr('user_id');

                   $.ajax({
                       type:'GET',
                       url:'{{url('/savings/amount/member')}}',
                       data:  {user_id:user_id},
                       success:function(response)
                       {
                           $("#exampleModalLong").modal('show');
                           $('#exampleModalLongTitle').text('All saving');

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
               $("#withdrawal_amount_member").click(function (){
                   var user_id=$(this).attr('user_id');

                   $.ajax({
                       type:'GET',
                       url:'{{url('/withdrawal/amount/member')}}',
                       data:  {user_id:user_id},
                       success:function(response)
                       {
                           $("#exampleModalLong").modal('show');
                           $('#exampleModalLongTitle').text('All withdrawal');

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