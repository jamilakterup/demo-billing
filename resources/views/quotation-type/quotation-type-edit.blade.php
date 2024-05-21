@extends('layouts.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Edit Quotation Type</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Quotation Type</li>
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
                          <div class="card-title">Edit Quotation Type</div>
                          <div class="card-tools">
                              <a class="btn-sm btn-primary"  href="{{route('quotationType.index')}}"><i class="fa fa-caret-left"></i> Back</a>
                          </div>
                      </div>
                      <div class="card-body">
                      {{Form::model($invoicType,['route'=>['quotationType.update',$invoicType->id],'method'=>'PUT','class'=>'form p-3 border rounded'])}}
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputEmail1">Quotation type name</label>
                                    {{Form::text('quotation_type_name',null,['class'=>'form-control','placeholder'=>'Quotation type name'])}}
                                    <div class="text-danger">{{$errors->first('quotation_type_name')}}</div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="inputEmail1">Quotation type short name</label>
                                    {{Form::text('quotation_type_short_name',null,['class'=>'form-control','placeholder'=>'Quotation type short name'])}}
                                    <div class="text-danger">{{$errors->first('quotation_type_short_name')}}</div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="inputEmail1">Signature</label>
                                    <select class="form-control" name="employee_id">
                                        <option value="">--Please select--</option>
                                      @foreach($employees as $employee)
                                          
                                        <option value="{{$employee->id}}" {{$employee->id==$invoicType->employee_id?'selected':''}}>
                                          <div class="d-flex flex-row">
                                            <div>{{$employee->name}}</div>
                                            <div>{{$employee->designation->name}}</div>
                                          </div>
                                        </option>
                                      @endforeach
                                    </select>
                                    <div class="text-danger">{{$errors->first('employee_id')}}</div>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="subject">Subject</label>
                                    {{Form::textarea('subject',null,['class'=>'form-control','placeholder'=>'Subject','rows'=>'2'])}}
                                    <div class="text-danger">{{$errors->first('subject')}}</div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="inputEmail1">Description</label>
                                    {{Form::textarea('description',null,['class'=>'form-control','placeholder'=>'Description','rows'=>'2'])}}
                                    <div class="text-danger">{{$errors->first('description')}}</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-start">
                                {{Form::submit('Update',['class'=>'btn btn-primary'])}}
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
    </script>
  @endpush


@endsection
