
@extends('backend.layout')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Instalment</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Instalment</li>
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
                            <div class="card-title">All instalments</div>
                            <div class="card-tools">
                                <div class="card-tools">
                                    <a  href="{{route('profileInstalment.create',['user_id'=>Auth::user()->id])}}" title="add new member" class="btn-sm btn-primary" data-card-widge="collapse">
                                        <i class="fas fa-plus-circle"></i>
                                    </a>
                                </div>
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
                                    <th>User</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($instalments as $instalment)
                                    <tr>
                                        <td>{{$i++}}</td>


                                        <td>{{$instalment->loan_id}}</td>
                                        <td><img src="{{asset('img/members/'.$instalment->loanID->memberName->photo)}}" class="img-circle img-size-32 mr-2">{{$instalment->loanID->memberName->name}}</td>
                                        <td>{{$instalment->userName->name}}</td>
                                        <td>{{$instalment->payment}}</td>
                                        <td>{{$instalment->payment_date}}</td>



                                        <td>
                                            <a href="{{route('profileInstalment.edit',$instalment->id)}}" title="edit" class="btn-sm btn-success "><i class="fas fa-edit"></i></a>
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