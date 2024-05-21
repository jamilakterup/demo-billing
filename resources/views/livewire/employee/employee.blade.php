<div>
    @include('livewire.employee.employee-create')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h4 class="m-0 text-uppercase"><b>Employee</b></h4>
              </div><!-- /.col -->
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Employee</li>
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
                            <div class="card-title">Employees</div>
                            <div class="card-tools">
                                <a class="btn-sm btn-primary"  href="#" wire:click.prevent="addNewEmployee"><i class="far fa-plus-square"></i> Create Employee</a>
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
                                        <th>Designation</th>
                                        <th>Signature</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$employee->name}}</td>
                                            <td>{{$employee->designation->name}}</td>
                                            <td>
                                                <img src="{{asset('/'.$employee->signature)}}" height="30px">
                                            </td>


                                            <td>
                                                <a class="btn btn-outline-primary btn-sm" wire:click.prevent="editEmployee({{$employee}})"><i class="fas fa-edit"></i></a>
                                                <a class="btn btn-outline-danger btn-sm" wire:click.prevent="delete({{$employee->id}})" ><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    {{-- {{route('category.delete',$category->id)}} --}}

                                </tbody>
                            </table>
                            <div class="mt-2">{{$employees->links('livewire.custom-pagination')}}</div>
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
        window.addEventListener('employee-store',function(e){
            $('#employee-create-modal').modal('hide');
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
            $('#employee-create-modal').modal('show');
        });


    </script>
@endpush


