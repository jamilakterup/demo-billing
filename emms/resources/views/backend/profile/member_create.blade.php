
@extends('backend.profile_layout')
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
                            <div class="card-title">Create member</div>
                            <div class="card-tools">
                                <a  href="{{url()->previous()}}" class="btn btn-tool" data-card-widge="collapse">
                                    <i class="fas fa-backward"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">

                            {{Form::open(['route'=>'profile.member.store','class'=>'form','files'=>true])}}

                            <fieldset class="border p-4 my-2">
                                <legend class="w-auto"> User  </legend>


                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">User name</label>
                                    <div class="col-sm-10">
                                        {{Form::hidden('user_id',$user->id,['class'=>'form-control'])}}
                                        {{Form::text('user_name',$user->name,['class'=>'form-control','readonly'=>'true'])}}
                                        <span class="text-danger">{{$errors->first('user_name')}}</span>
                                    </div>
                                </div>
                            </fieldset>


{{--                            personal information--}}

                            <fieldset class="border p-4">
                                <legend class="w-auto"> Personal Information </legend>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Name</label>
                                            <div class="col-sm-9">
                                                {{Form::text('name',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('name')}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Gender</label>
                                            <div class="col-sm-9">
                                                {{Form::select('gender',[''=>'--please select--','male'=>'Male','female'=>'Female'],null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('gender')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Father name</label>
                                            <div class="col-sm-9">
                                                {{Form::text('father_name',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('father_name')}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Education</label>
                                            <div class="col-sm-9">
                                                {{Form::text('education',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('education')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Mother name</label>
                                            <div class="col-sm-9">
                                                {{Form::text('mother_name',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('mother_name')}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Date of birth</label>
                                            <div class="col-sm-9">
                                                {{Form::text('dob',null,['class'=>'form-control datetime','id'=>'datetime'])}}
                                                <span class="text-danger">{{$errors->first('dob')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Spouse name</label>
                                            <div class="col-sm-9">
                                                {{Form::text('spouse_name',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('spouse_name')}}</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Occupation</label>
                                            <div class="col-sm-9">
                                                {{Form::text('occupation',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('occupation')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">NID</label>
                                            <div class="col-sm-9">
                                                {{Form::text('nid',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('nid')}}</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Income</label>
                                            <div class="col-sm-9">
                                                {{Form::text('monthly_income',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('monthly_income')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Photo</label>
                                            <div class="col-sm-9">
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

                                    <div class="form-group col-sm-6">

                                    </div>
                                </div>


                            </fieldset>

                            {{--contact--}}
                            <fieldset class="border p-4 my-2">
                                <legend class="w-auto"> Contact  </legend>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Mobile number</label>
                                            <div class="col-sm-9">
                                                {{Form::text('phone',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('phone')}}</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Email address</label>
                                            <div class="col-sm-9">
                                                {{Form::text('email',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('email')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                            <fieldset class="border p-4 my-2">
                                <legend class="w-auto"> Address </legend>


                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Village</label>
                                            <div class="col-sm-9">
                                                {{Form::text('vill',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('vill')}}</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Police station</label>
                                            <div class="col-sm-9">
                                                {{Form::text('ps',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('ps')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">Post office</label>
                                            <div class="col-sm-9">
                                                {{Form::text('post',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('post')}}</span>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group col-sm-6">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label">District</label>
                                            <div class="col-sm-9">
                                                {{Form::text('dist',null,['class'=>'form-control'])}}
                                                <span class="text-danger">{{$errors->first('dist')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>

                            <div class="form-group">

                                {{Form::submit('submit',['class'=>'btn btn-primary'])}}

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