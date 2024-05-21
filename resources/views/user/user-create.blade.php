@extends('layouts.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Create Users</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Users</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
              <div class="col-12 col-sm-12 col-md-12">
                  <div class="card">
                      <div class="card-header">
                          <div class="card-title">Create User</div>
                          <div class="card-tools">
                              <a class="btn-sm btn-primary" href="{{url()->previous()}}"><i class="fas fa-arrow-circle-left"></i> Back</a>
                          </div>
                      </div>
                      <div class="card-body">
                        {{Form::open(['route'=>'user.store','class'=>'form','files'=>true])}}
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="inputEmail1">Full name</label>
                                        {{Form::text('name',null,['class'=>'form-control','placeholder'=>'Name','id'=>'inputName1'])}}
                                        <div class="text-danger">{{$errors->first('name')}}</div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputEmail2">Email </label>
                                        {{Form::text('email',null,['class'=>'form-control','placeholder'=>'Email','id'=>'inputName2'])}}
                                        <div class="text-danger">{{$errors->first('email')}}</div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputEmail3">Mobile </label>
                                        {{Form::text('mobile',null,['class'=>'form-control','placeholder'=>'Mobile','id'=>'inputName3'])}}
                                        <div class="text-danger">{{$errors->first('mobile')}}</div>
                                    </div>
                                </div>
                                <div class="form-row">

                                    <div class="form-group col-md-4">
                                        <label for="inputEmail4">Password </label>
                                        <input type="password" name="password" class="form-control" placeholder="Password" id="inputName4">
                                        {{-- {{Form::password('password',null,['class'=>'form-control','placeholder'=>'Password','id'=>'inputName4'])}} --}}
                                        <div class="text-danger">{{$errors->first('password')}}</div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputEmail5">Confirm Password </label>
                                        <input type="password" name="confirmed_password" class="form-control" placeholder="Confirmed password" id="inputName5">

                                        {{-- {{Form::password('confirmed_password',null,['class'=>'form-control','placeholder'=>'Confirmed password','id'=>'inputName5'])}} --}}
                                        <div class="text-danger">{{$errors->first('confirmed_password')}}</div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Photo</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" name="photo" class="custom-file-input" id="exampleInputFile">
                                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="text-danger">{{$errors->first('photo')}}</div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="inputEmail4">Assign Role</label>
                                        {{Form::select('roles[]',$roles,null,['class'=>'form-control select2','multiple'=>'multiple'])}}
                                        <div class="text-danger">{{$errors->first('role')}}</div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center">
                                    {{Form::submit('Add User',['class'=>'btn btn-primary'])}}
                                </div>
                            {{Form::close()}}



                      </div>
                  </div>
              </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

  @push('scripts')

    <script>
        $(function () {
        bsCustomFileInput.init();
        });
    </script>
  @endpush


@endsection
