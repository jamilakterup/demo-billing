
@extends('backend.layout')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Loan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Loan</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <div class="card-title">Update loan</div>
                            <div class="card-tools">
                                <a  href="{{url()->previous()}}" class="btn btn-tool" data-card-widge="collapse">
                                    <i class="fas fa-backward"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">


                            {{Form::model($loan,['route'=>['profileLoan.update',$loan->id],'class'=>'form','files'=>true,'method'=>'put'])}}

                            <div class="form-group col-sm-6 offset-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Member</label>
                                    <div class="col-sm-8">
                                        {{Form::hidden('user_id',null,['class'=>'form-control','readonly'=>true])}}
                                        {{Form::hidden('member_id',null,['class'=>'form-control','readonly'=>true])}}
                                        {{Form::text('member_name',$loan->memberName->name,['class'=>'form-control','readonly'=>true])}}
                                        <span class="text-danger">{{$errors->first(' member name')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Loan amount</label>
                                    <div class="col-sm-8">
                                        {{Form::text('principal',null,['class'=>'form-control'])}}
                                        <span class="text-danger">{{$errors->first('principal')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Paid amount</label>
                                    <div class="col-sm-8">
                                        {{Form::text('paid',$loan->principal+$loan->interest,['class'=>'form-control'])}}
                                        <span class="text-danger">{{$errors->first('paid')}}</span>
                                    </div>
                                </div>
                            </div>



                            <div class="form-group col-sm-6 offset-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Total instalment</label>
                                    <div class="col-sm-8">
                                        {{Form::text('number_of_instalment',null,['class'=>'form-control'])}}
                                        <span class="text-danger">{{$errors->first('number_of_instalment')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Instalment type</label>
                                    <div class="col-sm-8">
                                        {{Form::select('instalment_type',$instalment_type_array,null,['class'=>'form-control'])}}
                                        <span class="text-danger">{{$errors->first('instalment_type')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Start date</label>
                                    <div class="col-sm-8">
                                        {{Form::text('instalment_start_date',null,['class'=>'form-control datetime','id'=>'datetime'])}}
                                        <span class="text-danger">{{$errors->first('instalment_start_date')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label"></label>
                                    <div class="col-sm-8">
                                        {{Form::submit('submit',['class'=>'btn btn-primary'])}}
                                    </div>
                                </div>


                            </div>

                            {{Form::close()}}


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