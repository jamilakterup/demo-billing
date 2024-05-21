<div>
    @include('livewire.product-type.product-type-create')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Product type</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Product type</li>
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
                            <div class="card-title">Product types</div>
                            <div class="card-tools">
                                <a class="btn-sm btn-primary"  href="#" wire:click.prevent="addNewProductType"><i class="far fa-plus-square"></i> Create Type</a>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($types as $type)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$type->name}}</td>



                                            <td>
                                                <a class="btn btn-outline-primary btn-sm" wire:click.prevent="editProductType({{$type}})"><i class="fas fa-edit"></i></a>
                                                <a class="btn btn-outline-danger btn-sm" wire:click.prevent="delete({{$type->id}})" ><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- {{route('category.delete',$category->id)}} --}}

                                </tbody>
                            </table>
                            <div class="mt-2">{{$types->links('livewire.custom-pagination')}}</div>

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
        window.addEventListener('product-type-store',function(e){
            $('#product-type-create-modal').modal('hide');
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
            $('#product-type-create-modal').modal('show');
        });


    </script>
@endpush


