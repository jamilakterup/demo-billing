<div>
    @include('livewire.module.module-create')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Module</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">User</li>
                  <li class="breadcrumb-item active">Module</li>
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
                            <div class="card-title">Module</div>
                            <div class="card-tools">
                                <a class="btn-sm btn-primary"  href="#" wire:click.prevent="addNewModule"><i class="far fa-plus-square"></i> Create module</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <input type="text" wire:model="searchField" class="form-control w-25  ml-auto" placeholder="Search.."/>
                            </div>

                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($modules as $module)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td class="text-capitalize">{{$module->name}}</td>




                                            <td>
                                                <a class="btn btn-outline-primary btn-sm" wire:click.prevent="editModule({{$module}})"><i class="fas fa-edit"></i></a>
                                                <a class="btn btn-outline-danger btn-sm" wire:click.prevent="delete({{$module->id}})" ><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- {{route('category.delete',$category->id)}} --}}

                                </tbody>
                            </table>
                            <div class="mt-2">{{$modules->links('livewire.custom-pagination')}}</div>

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
        window.addEventListener('module-store',function(e){
            $('#module-create-modal').modal('hide');
            // Swal.fire(
            // 'Saved!',
            // e.detail.title,
            // 'success'
            // );

            Swal.fire({
                toast: true,
                icon: 'success',
                title: e.detail.title,
                animation: false,
                position: 'top-right',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        });


        window.addEventListener('show-modal',function(e){
            $('#module-create-modal').modal('show');
        });


    </script>
@endpush


