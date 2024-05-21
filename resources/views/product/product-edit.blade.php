@extends('layouts.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Edit Customers</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Customer</li>
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
                          <div class="card-title">Edit Customer</div>
                          <div class="card-tools">
                            <a class="btn-sm btn-primary" href="{{url()->previous()}}"><i class="fas fa-arrow-circle-left"></i> Back</a>
                          </div>
                      </div>
                      <div class="card-body">
                        {{Form::model($customer,['route'=>['customer.update',$customer->id],'method'=>'PUT','files'=>true])}}
                            <fieldset class="form-group border p-3">
                                <legend class="w-auto px-2" style="font-size: 16px">Basic Customer Information</legend>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputEmail4">Name</label>
                                    {{Form::text('name',null,['class'=>'form-control','placeholder'=>'Name','id'=>'inputName4'])}}
                                    <div class="text-danger">{{$errors->first('name')}}</div>
                                  </div>

                                  <div class="form-group col-md-6">
                                    <label for="inputDname4">Display Name</label>
                                    {{Form::text('display_name',null,['class'=>'form-control','placeholder'=>'Display name','id'=>'inputName4'])}}
                                    <div class="text-danger">{{$errors->first('display_name')}}</div>
                                  </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputEmail4">Email</label>
                                    {{Form::text('email',null,['class'=>'form-control','placeholder'=>'Email','id'=>'inputEmail4'])}}
                                    <div class="text-danger">{{$errors->first('email')}}</div>
                                  </div>

                                  <div class="form-group col-md-6">
                                    <label for="inputPhone4">Phone</label>
                                    {{Form::text('phone',null,['class'=>'form-control','placeholder'=>'Phone','id'=>'inputPhone4'])}}
                                    <div class="text-danger">{{$errors->first('phone')}}</div>
                                  </div>
                                </div>
                            </fieldset>



                            <fieldset class="form-group border p-3">
                                <legend class="w-auto px-2" style="font-size: 16px">Customer Company Information</legend>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputCname4">Company Name</label>
                                    {{Form::text('company_name',null,['class'=>'form-control','placeholder'=>'Company name','id'=>'inputCname4'])}}
                                    <div class="text-danger">{{$errors->first('company_name')}}</div>
                                  </div>

                                  <div class="form-group col-md-6">
                                    <label for="inputCemail4">Company Email</label>
                                    {{Form::text('company_email',null,['class'=>'form-control','placeholder'=>'Company email','id'=>'inputCemail4'])}}
                                    <div class="text-danger">{{$errors->first('company_email')}}</div>
                                  </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputCPhone4">Company Phone</label>
                                    {{Form::text('company_phone',null,['class'=>'form-control','placeholder'=>'Company phone','id'=>'inputCPhone4'])}}
                                    <div class="text-danger">{{$errors->first('company_phone')}}</div>
                                  </div>

                                  <div class="form-group col-md-6">
                                    <label for="inputCweb4">Company website</label>
                                    {{Form::text('company_website',null,['class'=>'form-control','placeholder'=>'Company website','id'=>'inputCweb4'])}}
                                    <div class="text-danger">{{$errors->first('company_website')}}</div>
                                  </div>
                                </div>

                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="inputCaddress4">Company Address</label>
                                    {{Form::textarea('company_address',null,['class'=>'form-control','rows'=>'2','placeholder'=>'Company address','id'=>'inputCaddress4'])}}
                                    <div class="text-danger">{{$errors->first('company_address')}}</div>
                                  </div>

                                  <div class="form-group col-md-6">
                                    <label for="exampleInputFile">Company Logo </label>
                                    <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="company_logo" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    </div>
                                    <div class="text-danger">{{$errors->first('company_logo')}}</div>
                                  </div>
                                </div>

                            </fieldset>

                            <div class="d-flex justify-content-center">

                                {{Form::submit('Add Customer',['class'=>'btn btn-primary'])}}
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
