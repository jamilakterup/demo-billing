
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
                        <li class="breadcrumb-item active">Create</li>
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
                            <div class="card-title">Create Instalment Report</div>
                            <div class="card-tools">
                                <a  href="{{url()->previous()}}" class="btn btn-tool" data-card-widge="collapse">
                                    <i class="fas fa-backward"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">

                            {{Form::open(['route'=>'report.daily.search','class'=>'form','files'=>true])}}

                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">User</label>
                                    <div class="col-sm-8">
                                        {{Form::select('user_id',$user_array,null,['class'=>'form-control','id'=>'user_id'])}}
                                        <span class="text-danger">{{$errors->first(' User name')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Member</label>
                                    <div class="col-sm-8">
                                        {{Form::select('member_id',$member_array,null,['class'=>'form-control','id'=>'member_id'])}}
                                        <span class="text-danger">{{$errors->first('member_id')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Loan ID</label>
                                    <div class="col-sm-8">
                                        {{Form::select('loan_id',$loan_array,null,['class'=>'form-control','id'=>'loan_id'])}}
                                        <span class="text-danger">{{$errors->first('loan id')}}</span>
                                    </div>
                                </div>
                            </div>

{{--                            <div class="form-group col-sm-6 offset-md-3">--}}
{{--                                <div class="row">--}}
{{--                                    <label class="col-sm-4 col-form-label">Member</label>--}}
{{--                                    <div class="col-sm-8">--}}
{{--                                        {{Form::text('member',null,['class'=>'form-control','id'=>'member','readonly'=>true])}}--}}
{{--                                        <span class="text-danger">{{$errors->first(' member name')}}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">From date</label>
                                    <div class="col-sm-8">
                                        {{Form::text('from_date',null,['class'=>'form-control datetime'])}}
                                        <span class="text-danger">{{$errors->first('from_date')}}</span>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">To date</label>
                                    <div class="col-sm-8">
                                        {{Form::text('to_date',null,['class'=>'form-control datetime'])}}
                                        <span class="text-danger">{{$errors->first('to_date')}}</span>
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
            $('.datetime').datetimepicker({

                format: 'Y-m-d',

            });


            ///loan select

            $(document).ready(function(){



                $("#user_id").change(function (){
                    var user_id=$(this).val();

                    $('#member_id').empty();

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
                                    htmlOption+="<option value='"+v.id+"'>"+v.id+'-'+'('+v.name+')'+"</option>";
                                });
                            });
                            $('#member_id').append(htmlOption);

                        }
                    });
                });


                $("#member_id").change(function (){
                    var member_id=$(this).val();

                    $('#loan_id').empty();

                    $.ajax({
                        type:'GET',
                        url:'{{url('/report/loan/filter/')}}/'+member_id,

                        data:{id:member_id},
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

