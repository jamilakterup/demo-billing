@extends('layouts.master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Agreement</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Agreement</li>
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
              <div class="card-title">Agreement</div>
              <div class="card-tools">
                <a class="btn-sm btn-primary" href="{{route('agreement.create')}}"><i class="far fa-plus-square"></i>
                  Create Agreement</a>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <tr class="text-center">
                      <th>SL.</th>
                      <th>Agreement Number</th>
                      <th>Date</th>
                      <th>Customer</th>
                      <th>Status</th>
                      <th>Amount</th>
                      {{-- <th>Discount</th> --}}
                      {{-- <th>Due Amount</th> --}}
                      <th>Documents</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($agreements as $agreement)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>Agreement-{{$agreement->number}}</td>
                      <td>{{$agreement->agreement_date}}</td>
                      <td>{{$agreement->customer->name}}</td>
                      <td>
                        @if ($agreement->status=='draft')
                        <span class="badge bg-secondary text-uppercase">
                          {{$agreement->status}}
                        </span>
                        @elseif ($agreement->status=='sent')
                        <span class="badge bg-primary text-uppercase">
                          {{$agreement->status}}
                        </span>
                        @elseif ($agreement->status=='accepted')
                        <span class="badge bg-success text-uppercase">
                          {{$agreement->status}}
                        </span>
                        @elseif ($agreement->status=='rejected')
                        <span class="badge bg-danger text-uppercase">
                          {{$agreement->status}}
                        </span>
                        @elseif ($agreement->status=='converted')
                        <span class="badge bg-info text-uppercase">
                          {{$agreement->status}}
                        </span>
                        @endif

                      </td>
                      <td>

                        <?php
                                              $grandTotal=$agreement->agreement_details()->sum('total');
                                              $grandTotal=($grandTotal-$agreement->discount)+$agreement->vat+$agreement->tax;
                                          ?>
                        {{$grandTotal}}
                      </td>
                      <td>
                        <a class="btn btn-outline-primary btn-sm" href="{{route('show_work_order',$agreement->id)}}"><i
                            class="fa-solid fa-laptop-file"></i></a>

                        <a class="btn btn-outline-primary btn-sm"
                          href="{{route('show_work_completion',$agreement->id)}}"><i
                            class="fa-solid fa-certificate"></i></a>
                      </td>

                      <td>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <a class="btn btn-outline-primary btn-sm" href="{{route('agreement.show',$agreement->id)}}"><i
                              class="fas fa-eye"></i></a>

                          <a class="btn btn-outline-primary btn-sm mx-2"
                            href="{{route('agreement.edit',$agreement->id)}}"><i class="fas fa-edit"></i></a>

                          <a href="#" class="btn btn-outline-danger btn-sm delete" delid="{{$agreement->id}}"><i
                              class="fas fa-trash"></i></a>
                        </div>
                        {{Form::open(['route'=>['agreement.destroy',$agreement->id],'method'=>'DELETE','id'=>'destroy_form_'.$agreement->id])}}
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
</script>
@endpush


@endsection