
@extends('backend.layout')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Members</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Member</a></li>
                        <li class="breadcrumb-item active">show</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->



            <div class="row">
                <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0">
                                <h4 class="card-title">Member details</h4>
                                <div class="card-tools">
                                <a  href="{{url()->previous()}}" class="btn btn-tool" data-card-widge="collapse">
                                    <i class="fas fa-backward"></i>
                                </a>
                                </div>

                            </div>
                            <div class="card-body p-0">

                                <table class="table table-valign-middle table-responsive-sm">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>

                                    </tr>
                                    </thead>
                                    @php
                                        $i=1;
                                    @endphp
                                    <tbody>
                                    <tr>
                                        <th>User Name</th>
                                        <td>{{$member->agentName->name}}</td>

                                        <th>Gender</th>
                                        <td>{{$member->gender}}</td>
                                    </tr>
                                    <tr>
                                        <th>Member Name</th>
                                        <td>{{$member->name}}</td>

                                        <th>Mobile</th>
                                        <td>{{$member->phone}}</td>
                                    </tr>
                                    <tr>
                                        <th>Father Name</th>
                                        <td>{{$member->father_name}}</td>
                                        <th>Email</th>
                                        <td>{{$member->email}}</td>
                                    </tr>
                                    <tr>
                                        <th>Mother Name</th>
                                        <td>{{$member->mother_name}}</td>
                                        <th>National ID</th>
                                        <td>{{$member->nid}}</td>
                                    </tr>

                                    <tr>
                                        <th>Spouse Name</th>
                                        <td>{{$member->spouse_name}}</td>
                                        <th>Village</th>
                                        <td>{{$member->vill}}</td>
                                    </tr>

                                    <tr>
                                        <th>Occupation</th>
                                        <td>{{$member->occupation}}</td>
                                        <th>Post office</th>
                                        <td>{{$member->post}}</td>
                                    </tr>

                                    <tr>
                                        <th>Monthly income</th>
                                        <td>{{$member->monthly_income}}</td>
                                        <th>Police station</th>
                                        <td>{{$member->ps}}</td>
                                    </tr>

                                    <tr>
                                        <th>Date of Birth</th>
                                        <td>{{$member->dob}}</td>
                                        <th>District</th>
                                        <td>{{$member->dist}}</td>
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
                            <h4>Loan details</h4>
                            

                        </div>
                        <div class="card-body p-0">

                            <table class="table table-valign-middle table-responsive-sm">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Loan ID</th>
                                    <th>Amount</th>
                                    <th>Interest</th>
                                    <th>Instalment</th>
                                    <th>Paid Instalment</th>
                                    <th>Total paid</th>
                                    <th>Status</th>

                                </tr>
                                </thead>
                                @php
                                    $i=1;
                                @endphp
                                <tbody>
                                @foreach($loans as $loan)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$loan->id}}</td>
                                        <td>{{$loan->principal}}</td>
                                        <td>{{$loan->interest}}</td>
                                        <td>{{$loan->number_of_instalment}}</td>
                                        <td>{{\App\Helper::instalment_count($loan->id)}}</td>
                                        <td>{{\App\Helper::total_payment($loan->id)}}</td>
                                        <td>
                                            @if($loan->paid==0)
                                                <span class="badge badge-warning">Pending</span>
                                            @else
                                                <span class="badge badge-success">Completed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach


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
                            <h4>Savings details</h4>

                        </div>
                        <div class="card-body p-0">

                            <table class="table table-valign-middle table-responsive-sm">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Savings Id
                                    <th>Date</th>
                                    <th>Type
                                    <th>Amount</th>

                                </tr>
                                </thead>
                                @php
                                    $i=1;
                                $total_savings=0;
                                @endphp
                                <tbody>
                                @foreach($savings as $saving)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$saving->id}}</td>
                                        <td>{{$saving->date}}</td>
                                        <td>{{$saving->type}}</td>
                                        <td>
                                            @if($saving->type=='withdrawal')
                                            <span class="text-danger">{{-$saving->amount}}</span>
                                            @else
                                                {{$saving->amount}}
                                            @endif

                                        </td>
                                        <?php
                                        $total_savings+=$saving->type=='withdrawal'?-$saving->amount:$saving->amount;
                                        ?>

                                    </tr>
                                @endforeach

                                    <tr>
                                        <th colspan="4" class="text-center">Total Savings</th>
                                        <td>{{$total_savings}}</td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div><!--/. container-fluid -->
    </section>

@stop