
@extends('backend.layout')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">User Details</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="mr-2"><img class="img-circle" src="{{asset('img/users/'.$user->photo)}}" width=30px></li>
                        <li class="">{{$user->name}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- Info boxes -->

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


            <!-- /.endcol-md-6 -->



            <!-- /.col-md-6 -->
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
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Received amount</th>
                                        <td>{{$received_current_date}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Pending amount</th>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Total savings</th>
                                        <td>{{$savings_current_date}}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Total withdrawal</th>
                                        <td>{{$withdrawal_current_date}}</td>
                                        <td></td>
                                    </tr>
                                    
                                
                                </tbody>
                            </table>
                        </div>

                </div>
            </div>

            <!-- /.col-md-6 -->

        </div>

        {{-- all member of this user--}}
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="all_member">
                    <div class="card-header">
                        <div class="card-title">All member</div>
                        
                    </div>
                    <div class="card-body">
                        @php
                            $i=1;
                            @endphp

                            <table class="table  table-striped table-valign-middle table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Photo</th>
                                        <th>User</th>
                                        <th>Loan</th>
                                        <th>Savings</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach($members as $member)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$member->name}}</td>
                                        <td>{{$member->phone}}</td>
                                        <td>
                                            <div class="img-container">
                                                <img class="big-img img-thumbnail" src="{{asset('img/members/'.$member->photo)}}" width="100px">
                                                <img class="img-thumbnail" src="{{asset('img/members/'.$member->photo)}}" width="30px">
                                            </div>
                                        </td>
                                        <td>{{$member->agentName->name}}</td>
                                        <td><a href="{{route('loan.create',['member_id'=>$member->id])}}" title="Loan" class="btn-sm btn-primary"><i class="fas fa-dollar-sign"></i></a></td>
                                        <td><a href="{{route('savings.create2',['member_id'=>$member->id])}}" title="Loan" class="btn-sm btn-primary"><i class="fas fa-sack-dollar"></i></a></td>

                                        <td>
                                            <a href="{{route('member.show',$member->id)}}" title="show details" class="btn-sm btn-info"><i class="fas fa-eye"></i></a>

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

                            <table class="table table-hover">
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
                                            <a href="{{url('instalment/create2',$loan->id)}}" title="Instalment" class="btn-sm btn-primary"><i class="fa fa-dollar-sign"></i></a>
                                            @else
                                                <a href="javascript:void(0)" title="Instalment" class="btn-sm btn-secondary disabled"><i class="fa fa-dollar-sign"></i></a>
                                            @endif
                                            <a href="{{route('loan.show',$loan->id)}}" title="show details" class="btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                            <a href="{{route('loan.edit',$loan->id)}}" title="edit" class="btn-sm btn-success "><i class="fas fa-edit"></i></a>
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

        

        {{-- Receivable instalment --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Receivable Instalment</div>
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


           ///loan select

           $(document).ready(function(){
               $("#loan_id").change(function (){
                   var loan_id=$(this).val();


                   $.ajax({
                       type:'GET',
                       url:'{{url('/instalment/loan/select/')}}/'+loan_id,

                       data:{id:loan_id},
                       dataType: 'json',
                       success:function(response)
                       {



                          $('#interval').val(response.payment_interval);
                          $('#payment').val(response.payment);
                          $('#member').val(response.member);

                       }
                   });
               });


                ///user select

               $("#user_id").change(function (){
                   var user_id=$(this).val();

                   $('#loan_id').empty();

                   $.ajax({
                       type:'GET',
                       url:'{{url('/instalment/member/filter/')}}/'+user_id,

                       data:{id:user_id},
                       dataType: 'json',
                       success:function(response)
                       {



                           var htmlOption="<option value=''>--please select--</option>";
                           $.each(response, function() {
                               $.each(this, function(k, v) {
                                   htmlOption+="<option value='"+v.id+"'>"+v.id+'-'+'('+v.principal+')'+"</option>";
                               });
                           });
                           $('#loan_id').append(htmlOption);

                       }
                   });
               });
           });
       </script>
    @endpush

@stop

