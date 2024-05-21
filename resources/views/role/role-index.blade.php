@extends('layouts.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-uppercase"><b>Role</b></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">User</li>
                <li class="breadcrumb-item active">Role</li>
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
                          <div class="card-title">Role</div>
                          <div class="card-tools">
                              <a class="btn-sm btn-primary"  href="{{route('role.create')}}"><i class="far fa-plus-square"></i> Create Role</a>
                          </div>
                      </div>
                      <div class="card-body">
                          <div class="table-responsive">

                              <table id="example1" class="table table-bordered">
                                  <thead>
                                      <tr>
                                          <th>SL.</th>
                                          <th>Role</th>
                                          <th width="70%">Permission</th>

                                          <th>Action</th>
                                      </tr>
                                  </thead>
                                  <tbody>

                                      @foreach ($roles as $role)
                                          <tr>
                                              <td>{{$loop->iteration}}</td>
                                              <td>{{$role->name}}</td>
                                              <td>
                                                  <div class="d-flex justify-content-center flex-wrap">
                                                      @foreach ($role->permissions as $permission)
                                                      <span class="py-1 px-2 bg-info m-1">{{$permission->name}}</span>
                                                      @endforeach
                                                  </div>
                                              </td>

                                              <td>
                                                  <a href="{{route('role.edit',$role->id)}}" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                                                  <a href="#" class="btn btn-outline-danger btn-sm delete" delid="{{$role->id}}" ><i class="fas fa-trash"></i></a>
                                                  {{Form::open(['route'=>['role.destroy',$role->id],'method'=>'DELETE','id'=>'destroy_form_'.$role->id])}}
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
