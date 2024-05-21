<div>

  @include('livewire.user.userCreate')
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
                            <a class="btn-sm btn-primary"  wire:click.prevent="createUser" href=""><i class="far fa-plus-square"></i> Create User</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SL.</th>
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
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>

                                        <td>

                                            <a class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>
                                            <a class="btn btn-outline-danger btn-sm" id="delete" ><i class="fas fa-trash"></i></a>
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


</div>

@push('scripts')

  <script>
        window.addEventListener('create-form', function(e) {
        $('#exampleModal').modal().show();
        // Swal.fire({
        //     title: '<h3 style="border-bottom: 1px solid #ddd; padding-bottom: 5px">'+e.detail.title+'</h3>',
        //     html: e.detail.html,
        //     showConfirmButton: false,
        // });
        });
  </script>
@endpush
