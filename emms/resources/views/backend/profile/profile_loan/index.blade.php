
@extends('backend.profile_layout')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Loan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Loan</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">All loan</div>
                            <div class="card-tools">
{{--                                <a  href="{{route('loan.create')}}" title="add new member" class="btn-sm btn-primary" data-card-widge="collapse">--}}
{{--                                    <i class="fas fa-plus-circle"></i>--}}
{{--                                </a>--}}
                            </div>
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
                                            <a href="{{url('profile/instalment/create2',$loan->id)}}" title="Instalment" class="btn-sm btn-primary"><i class="fa fa-dollar-sign"></i></a>
                                            @else
                                                <a href="javascript:void(0)" title="Instalment" class="btn-sm btn-secondary disabled"><i class="fa fa-dollar-sign"></i></a>
                                            @endif
                                            <a href="{{route('profileLoan.show',$loan->id)}}" title="show details" class="btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                            <a href="{{route('profileLoan.edit',$loan->id)}}" title="edit" class="btn-sm btn-success "><i class="fas fa-edit"></i></a>
                                            <a href="" title="delete" class="btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>


                                            {{--                                            {{Form::open(['route'=>['member.destroy',$member->id,],'method'=>'delete','class'=>'form form-inline'])}}--}}
                                            {{--                                                {{Form::submit('Yes',['class'=>'btn btn-danger'])}}--}}
                                            {{--                                                <button type="submit" class="btn-sm btn-secondary"><i class="fas fa-trash-alt"></i></button>--}}
                                            {{--                                            {{Form::close()}}--}}
                                            {{--                                                <a href="{{route('member.destroy',$member->id)}}" title="delete" class="btn btn-secondary"><i class="fas fa-trash-alt"></i></a>--}}

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

@stop