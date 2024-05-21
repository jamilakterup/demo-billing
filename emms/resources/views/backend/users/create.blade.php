
@extends('backend.layout')
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">User</a></li>
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
                            <div class="card-title">Create user</div>
                            <div class="card-tools">
                                <a  href="{{url()->previous()}}" class="btn btn-tool" data-card-widge="collapse">
                                    <i class="fas fa-backward"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">

                            {{Form::open(['route'=>'user.store','class'=>'form','files'=>true])}}

                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Name</label>
                                    <div class="col-sm-8">
                                        {{Form::text('name',null,['class'=>'form-control','id'=>'name'])}}
                                        <span class="text-danger">{{$errors->first(' name')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Email</label>
                                    <div class="col-sm-8">
                                        {{Form::text('email',null,['class'=>'form-control','id'=>'email'])}}
                                        <span class="text-danger">{{$errors->first('email')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Mobile</label>
                                    <div class="col-sm-8">
                                        {{Form::text('mobile',null,['class'=>'form-control','id'=>'email'])}}
                                        <span class="text-danger">{{$errors->first('mobile')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Password</label>
                                    <div class="col-sm-8">
                                        {{Form::text('password',null,['class'=>'form-control'])}}
                                        <span class="text-danger">{{$errors->first('password')}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Confirm Password</label>
                                    <div class="col-sm-8">
                                        {{Form::text('con_pass',null,['class'=>'form-control',])}}
                                        <span class="text-danger">{{$errors->first('confirm password')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-6 offset-md-3">
                                <div class="row">
                                    <label class="col-sm-4 col-form-label">Photo</label>
                                    <div class="col-sm-8">
                                        {{Form::file('photo',null,['class'=>'form-control'])}}
                                        <span class="text-danger">{{$errors->first('photo')}}</span>
                                        <ul>
                                            <li class="text-danger" style="font-size: .8rem">File format must be .jpeg, .jpg or .png </li>
                                            <li class="text-danger" style="font-size: .8rem">Size should not exceed 1024 KB</li>
                                            <li class="text-danger" style="font-size: .8rem">Dimension preferable 300 X 300 px</li>
                                        </ul>
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

