@extends('layouts.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Customers</h1>
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
                          <div class="card-title">Customer</div>
                          <div class="card-tools">
                              <a class="btn-sm btn-primary"  href="{{route('customer.create')}}"><i class="far fa-plus-square"></i> Create Customer</a>
                          </div>
                      </div>
                      <div class="card-body">
                          <table id="example1" class="table table-bordered">
                              <thead>
                                  <tr>
                                      <th>SL.</th>
                                      <th>Name</th>
                                      <th>Email</th>
                                      <th>Invoice</th>
                                      <th>Due Amount</th>
                                      <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @php
                                      $i=1;
                                  @endphp
                                  @foreach ($customers as $customer)
                                      <tr>
                                          <td>{{$i++}}</td>
                                          <td>{{$customer->name}}</td>
                                          <td>{{$customer->email}}</td>
                                          <td></td>
                                          <td></td>

                                            <td>
                                              <a href="{{route('customer.show',$customer->id)}}" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i></a>
                                              <a href="{{route('customer.edit',$customer->id)}}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                                              <a href="{{route('customer.destroy',$customer->id)}}" class="btn btn-outline-danger btn-sm" id="delete" ><i class="fas fa-trash"></i></a>
                                              {{Form::open(['route'=>['customer.destroy',$customer->id],'method'=>'DELETE','id'=>'destroy-form'])}}
                                              {{Form::close()}}
                                            </td>
                                      </tr>
                                  @endforeach

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
