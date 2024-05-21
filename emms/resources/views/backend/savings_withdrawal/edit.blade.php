
@extends('backend.layout')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Savings withdrawal</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Savings withdrawal</a></li>
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
                            <div class="card-title">Edit savings withdrawal</div>
                            <div class="card-tools">
                                <a  href="{{url()->previous()}}" class="btn btn-tool" data-card-widge="collapse">
                                    <i class="fas fa-backward"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">

                            {{Form::model($savings_withdrawal,['route'=>['savings_withdrawal.update',$savings_withdrawal->id],'class'=>'form','files'=>true,'method'=>'put'])}}


                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Member</label>
                                    <div class="col-sm-8">
                                        {{Form::select('member_id',$member_array,null,['class'=>'form-control','id'=>'member'])}}
                                        <span class="text-danger">{{$errors->first(' member name')}}</span>
                                    </div>
                                </div>
                            </div>



                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Amount</label>
                                    <div class="col-sm-8">
                                        {{Form::text('amount',null,['class'=>'form-control','id'=>'payment'])}}
                                        <span class="text-danger">{{$errors->first('amount')}}</span>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Date</label>
                                    <div class="col-sm-8">
                                        {{Form::text('date',null,['class'=>'form-control','id'=>'datetime'])}}
                                        <span class="text-danger">{{$errors->first('date')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-md-3">
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

    @push('scripts')
        <script>
            $('#datetime').datetimepicker({

            });


            ///loan select

            $(document).ready(function(){


            });
        </script>
    @endpush

@stop

