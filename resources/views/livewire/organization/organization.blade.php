<div>
    {{-- @include('livewire.product.product-create') --}}
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Organization</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Organization</li>
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
                            <div class="card-title">Organization</div>
                            <div class="card-tools">
                                {{-- <a class="btn-sm btn-primary"  href="#" wire:click.prevent="addNewProduct"><i class="far fa-plus-square"></i> Create Product</a> --}}
                            </div>
                        </div>
                        <form wire:submit.prevent="organizationUpdate">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Name</label>
                                        <input type="text" wire:model.defer="state.name"  class="form-control @error('name') is-invalid @enderror" id="" aria-describedby="emailHelp" placeholder="Enter name">
                                        @error('name')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Email</label>
                                        <input type="text" wire:model.defer="state.email"  class="form-control @error('email') is-invalid @enderror" id="" aria-describedby="emailHelp" placeholder="Enter email">
                                        @error('email')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Mobile No.</label>
                                        <input type="text" wire:model.defer="state.mobile"  class="form-control @error('mobile') is-invalid @enderror" id="" aria-describedby="emailHelp" placeholder="Enter mobile no.">
                                        @error('mobile')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Phone</label>
                                        <input type="text" wire:model.defer="state.phone"  class="form-control @error('phone') is-invalid @enderror" id="" aria-describedby="emailHelp" placeholder="Enter phone no.">
                                        @error('phone')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Address</label>
                                        <textarea wire:model.defer="state.address"  class="form-control @error('address') is-invalid @enderror" id="" aria-describedby="emailHelp" placeholder="Enter mobile no."></textarea>
                                        @error('address')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputFile">Logo</label>
                                        <input type="file" wire:model="logo" class="custom-file-input" id="exampleInputFile">
                                        
                                        @error('logo')
                                        <div class="invalid-feedback">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
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
        window.addEventListener('update',function(e){
            //$('#product-create-modal').modal('hide');
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
            $('#product-create-modal').modal('show');
        });




    </script>

@endpush


