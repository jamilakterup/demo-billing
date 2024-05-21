@extends('layouts.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-uppercase font-weight-bold">Edit Role</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Role</li>
                <li class="breadcrumb-item active">Edit</li>
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
                    {{Form::model($role,['route'=>['role.update',$role->id],'method'=>'PUT'])}}
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Edit Role</div>
                                    <div class="card-tools">
                                        <a class="btn-sm btn-primary" href="{{url()->previous()}}"><i class="fas fa-arrow-circle-left"></i> Back</a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Role name</label>
                                    {{Form::text('name',null,['class'=>'form-control w-100'])}}
                                    <div class="text-danger">{{$errors->first('name')}}</div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>
                                                    <input id="all-check" type="checkbox" name="all-module-check"/>

                                                </th>
                                                <th>Module</th>
                                                <th>Permissions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($modules as $module)
                                            <?php
                                            $permissions=DB::table('permissions')->select('name','id')->where('module_id',$module->id)->get();
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="module-check" type="checkbox" {{\App\Models\User::roleHasPermissions($role,$permissions)?'checked':''}}>
                                                    </div>
                                                </td>
                                                <td class="text-capitalize">{{$module->name}} </td>
                                                <td>
                                                    @foreach ($module->permissions as $permission)





                                                        <div class="form-check">
                                                            {{-- php artisan permission:cache-reset --}}
                                                            <input class="form-check-input" name="permission[]" type="checkbox" value="{{$permission->id}}"  {{$role->hasPermissionTo($permission->name)?'checked':''}}>
                                                            <label class="form-check-label">
                                                                {{$permission->name}}
                                                            </label>
                                                        </div>


                                                    @endforeach
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- /.card-body -->

                            <div class="card-footer">
                                <div class="d-flex justify-content-center">
                                    {{Form::submit('Add Role',['class'=>'btn btn-primary text-uppercase font-weight-bold'])}}
                                </div>
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->




  @push('scripts')

    <script>


        $(document).ready(function(){

            $('#all-check').click(function(){
                if($(this).is(':checked')){
                    $('input[type=checkbox]').prop('checked',true);
                }
                else{
                    $('input[type=checkbox]').prop('checked',false);
                }
            });

        });



    </script>
  @endpush


@endsection
