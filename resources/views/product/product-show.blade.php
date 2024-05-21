@extends('layouts.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Customer Details</h1>
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
              <div class="col-12 col-sm-12 col-md-6 offset-3">
                  <div class="card">
                      <div class="card-header">
                          <div class="card-title">Customer Details</div>
                          <div class="card-tools">
                              <a class="btn-sm btn-primary"  href="{{url()->previous()}}"><i class="fas fa-arrow-circle-left"></i> Back</a>
                          </div>
                      </div>
                      <div class="card-body">
                          <table id="example1" class="table table-bordered" >
                              <tbody>
                                <tr>
                                    <th>Customer Name</th>
                                    <td>{{$customer->name}}</td>

                                </tr>
                                <tr>
                                    <th>Display Name</th>
                                    <td>{{$customer->display_name}}</td>

                                </tr>
                                <tr>
                                    <th>Customer Email</th>
                                    <td>{{$customer->email}}</td>
                                </tr>
                                <tr>
                                    <th>Customer Phone</th>
                                    <td>{{$customer->phone}}</td>
                                </tr>
                                <tr>
                                    <th>Company Name</th>
                                    <td>{{$customer->company_name}}</td>
                                </tr>
                                <tr>
                                    <th>Company Phone</th>
                                    <td>{{$customer->company_phone}}</td>
                                </tr>
                                <tr>
                                    <th>Company Email</th>
                                    <td>{{$customer->company_email}}</td>
                                </tr>
                                <tr>
                                    <th>Company address</th>
                                    <td>{{$customer->company_address}}</td>
                                </tr>
                                <tr>
                                    <th>Company Logo</th>
                                    <td>
                                        <img src="{{asset('logo/'.$customer->company_logo)}}" width="80px">
                                    </td>
                                </tr>


                              </tbody>
                          </table>
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
