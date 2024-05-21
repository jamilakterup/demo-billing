<div>
    @include('livewire.status.status-create')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Status</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Setting</li>
                  <li class="breadcrumb-item active">Status</li>
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
                            <div class="card-title">Status</div>
                            <div class="card-tools">
                                <a class="btn-sm btn-primary"  href="#" wire:click.prevent="addNewStatus"><i class="far fa-plus-square"></i> Create status</a>
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
                                        <th>ID</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>Color Code</th>
                                        <th>Color</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($statuses as $status)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$status->id}}</td>
                                            <td>{{$status->type}}</td>
                                            <td>{{$status->name}}</td>
                                            <td>{{$status->color}}</td>
                                            <td>
                                                <div style="width: 35px; height:35px; background-color:#{{$status->color}}">

                                                </div>
                                            </td>



                                            <td>
                                                <a class="btn btn-outline-primary btn-sm" wire:click.prevent="editStatus({{$status}})"><i class="fas fa-edit"></i></a>
                                                <a class="btn btn-outline-danger btn-sm" wire:click.prevent="delete({{$status->id}})" ><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- {{route('category.delete',$category->id)}} --}}

                                </tbody>
                            </table>
                            <div class="mt-2">{{$statuses->links('livewire.custom-pagination')}}</div>

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
        window.addEventListener('status-store',function(e){
            $('#status-create-modal').modal('hide');
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
            $('#status-create-modal').modal('show');
        });


    </script>
@endpush


