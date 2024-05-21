@extends('layouts.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Users</h1>
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
                          <div class="card-title">User</div>
                          <div class="card-tools">
                              <a class="btn-sm btn-primary"  href="{{route('user.create')}}"><i class="far fa-plus-square"></i> Create User</a>
                          </div>
                      </div>
                      <div class="card-body">
                          <table id="example1" class="table table-bordered">
                              <thead>
                                  <tr>
                                      <th>SL.</th>
                                      <th>Image</th>
                                      <th>Name</th>
                                      <th>Email</th>

                                      <th>Action</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @php
                                      $i=1;
                                  @endphp
                                  @foreach ($users as $user)
                                      <tr>
                                          <td>{{$i++}}</td>
                                          <td><img src="{{asset('photo/'.$user->profile_photo_path)}}" alt="{{$user->name}}" class="img-circle img-size-32 mr-2"></td>
                                          <td>{{$user->name}}</td>
                                          <td>{{$user->email}}</td>

                                          <td>

                                              <a href="{{route('user.edit',$user->id)}}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                                              <a href="#" class="btn btn-outline-danger btn-sm delete" delid="{{$user->id}}" ><i class="fas fa-trash"></i></a>
                                              {{Form::open(['route'=>['user.destroy',$user->id],'method'=>'DELETE','id'=>'destroy_form_'.$user->id])}}
                                              {{Form::close()}}
                                          </td>
                                      </tr>
                                  @endforeach
                                  {{-- {{route('category.delete',$category->id)}} --}}

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
