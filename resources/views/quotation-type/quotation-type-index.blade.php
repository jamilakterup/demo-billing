@extends('layouts.master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Quotation Type</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Quotation Type</li>
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
              <div class="card-title">Quotation Type</div>
              <div class="card-tools">
                <a class="btn-sm btn-primary" href="{{route('quotationType.create')}}"><i
                    class="far fa-plus-square"></i> New</a>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>SL.</th>
                      <th>Name</th>
                      <th>Short Name</th>
                      <th>Signature</th>
                      <th>Subject</th>
                      <th>Description</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($quotationTypes as $quotationType)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>{{$quotationType->quotation_type_name}}</td>
                      <td>{{$quotationType->quotation_type_short_name}}</td>
                      <td>
                        <div>{{$quotationType->employee->name}}</div>
                        <div>{{$quotationType->employee->designation->name}}</div>
                      </td>
                      <td>{{$quotationType->subject}}</td>
                      <td>{{$quotationType->description}}</td>

                      <td>
                        <div class="d-flex justify-content-center align-items-center">
                          <a href="{{route('quotationType.edit',$quotationType->id)}}"
                            class="btn btn-outline-primary btn-sm mr-2"><i class="fas fa-edit"></i></a>
                          <a class="btn btn-outline-danger btn-sm" id="delete" delid="{{$quotationType->id}}"><i
                              class="fas fa-trash"></i></a>
                        </div>
                        {{Form::open(['route'=>['quotationType.destroy',$quotationType->id],'method'=>'DELETE','id'=>'destroy-form'])}}
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
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->




@push('scripts')

<script>
  $(document).on('click','#delete',function(e){
            e.preventDefault();
            let delid = $(this).attr('delid');
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if(result.isConfirmed) {

                // Swal.fire(
                // 'Deleted!',
                // 'Your file has been deleted.',
                // 'success'
                // );
                $('#destroy-form').submit();
                //window.location.href=route;
            }

            });


    });

</script>
@endpush


@endsection