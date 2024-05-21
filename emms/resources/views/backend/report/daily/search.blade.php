
@extends('backend.layout')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Report</a></li>
                        <li class="breadcrumb-item"><a href="#">Instalment</a></li>
                        <li class="breadcrumb-item active">Search</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            @php
            $i=1;
            @endphp

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Instalment Report</h3>

                            <div class="card-tools">
{{--                                <a href="{{route('report.loan.download')}}" class="btn btn-tool btn-sm">--}}
{{--                                    <i class="fas fa-download"></i>--}}
{{--                                </a>--}}
                                {{Form::open(['route'=>'report.loan.download','class'=>'form','files'=>true])}}
                                    {{Form::hidden('user_id',$user_id,['class'=>'form-control','id'=>'user_id'])}}
                                    {{Form::hidden('member_id',$member_id,['class'=>'form-control','id'=>'member_id'])}}
                                    {{Form::hidden('loan_id',$loan_id,['class'=>'form-control','id'=>'loan_id'])}}
                                    {{Form::hidden('from_date',$from_date,['class'=>'form-control datetime'])}}
                                    {{Form::hidden('to_date',$to_date,['class'=>'form-control datetime'])}}
                                    {{Form::submit('Download',['class'=>'btn-sm btn-primary'])}}
                                {{Form::close()}}

                            </div>

                        </div>
                        <div class="card-body p-0">

                            <table class="table">
                                <tr>
                                    <th>Member name :</th>
                                    <td>
                                        @if(isset($member_name))
                                            {{$member_name}}
                                        @endif
                                    </td>

                                    <th>Date :</th>
                                    <td>
                                        @if(isset($from_date) && isset($to_date) )
                                            {{$from_date}} to {{$to_date}}
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <table class="table table-bordered table-valign-middle table-responsive-sm">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Loan ID</th>
                                    <th>Member ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>

                                </tr>
                                </thead>
                                @php
                                    $i=1;
                                $total_principal=0;
                                $total_interest=0;
                                $total_payment=0;
                                @endphp
                                <tbody>

                                @foreach($daily_reports as $daily_report)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$daily_report->loan_id}}</td>
                                        <td>{{$daily_report->member_id}}</td>
                                        <td>{{$daily_report->date}}</td>
                                        <td>{{$daily_report->payment}}</td>
                                       <?php
                                        $total_payment+=$daily_report->payment;
                                        ?>

                                    </tr>

                                @endforeach

                                <tr>
                                    <td colspan="4" align="center"><b>Total</b></td>
                                    <td><b>{{$total_payment}}</b></td>

                                </tr>





                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->


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

    @push('scripts')

    @endpush

@stop

