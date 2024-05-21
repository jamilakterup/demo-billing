
@extends('backend.layout')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Loan Details</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Loan</a></li>
                        <li class="breadcrumb-item active">show</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">


            <div class="row">


                <div class="col-md-6">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">Loan details</h3>

                            </div>
                            <div class="card-body p-0">

                                <table class="table table-valign-middle table-responsive-sm">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>

                                    </tr>
                                    </thead>
                                    @php
                                        $i=1;
                                    @endphp
                                    <tbody>
                                    <tr>
                                        <th>Loanee</th>
                                        <td>{{$loan->memberName->name}}</td>
                                    </tr>

                                    <tr>
                                        <th>Principal</th>
                                        <td>{{$loan->principal}}</td>
                                    </tr>

                                    <tr>
                                        <th>Interest</th>
                                        <td>{{$loan->interest}}</td>
                                    </tr>

                                    <tr>
                                        <th>Total Instalment</th>
                                        <td>{{$loan->number_of_instalment}}</td>
                                    </tr>

                                    <tr>
                                        <th>Instalment Interval</th>
                                        <td>{{$loan->inst_type->name}}</td>
                                    </tr>

                                    <tr>
                                        <th>Instalment Amount</th>
                                        <td>{{($loan->principal+$loan->interest)/$loan->number_of_instalment}}</td>
                                    </tr>

                                    <tr>
                                        <th>Paid Instalment</th>
                                        <td>{{$paid_instalment}}</td>
                                    </tr>

                                    <tr>
                                        <th>Total Paid</th>
                                        <td>{{$total_paid}}</td>
                                    </tr>

                                    <tr>
                                        <th>Instalment Start Date</th>
                                        <td>{{$loan->instalment_start_date}}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($loan->paid==0)
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-success">Completed</span>
                                            @endif
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="card-title">Payment Calender</h3>

                            </div>
                            <div class="card-body p-0">

                                <table class="table table-valign-middle table-responsive-sm">
                                    <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    @php
                                        $i=1;
                                    @endphp
                                    <tbody>
                                    @foreach($payment_calenders as $payment_calender)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$payment_calender->payment_date}}</td>
                                            <td>
                                                @if($payment_calender->status==0)
                                                    <span class="badge badge-warning">Pending</span>
                                                @else
                                                    <span class="badge badge-success">Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3 d-flex justify-content-center">
                                    <div>{{$payment_calenders->links()}}</div>
                                </div>

                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Info boxes -->




            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-8">
                    <!-- MAP & BOX PANE -->

                    <!-- /.card -->

                    <!-- /.row -->

                    <!-- TABLE: LATEST ORDERS -->

                    <!-- /.card -->
                </div>
                <!-- /.col -->


                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>

@stop