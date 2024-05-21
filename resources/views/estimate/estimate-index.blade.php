@extends('layouts.master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Quotation</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Quotation</li>
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
              <div class="card-title">Quotation</div>
              <div class="card-tools">
                <a class="btn-sm btn-primary" href="{{route('estimate.create')}}"><i class="far fa-plus-square"></i>
                  Create Quotation</a>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <tr class="text-center">
                      <th>SL.</th>
                      <th>Quotation Number</th>
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
                    @foreach ($estimates as $estimate)
                    <tr>
                      <td>{{$loop->iteration}}</td>
                      <td>Quotation-{{$estimate->number}}</td>
                      <td>{{$estimate->estimate_date}}</td>
                      <td>{{$estimate->customer->name}}</td>
                      <td>
                        @if ($estimate->status=='draft')
                        <span class="badge bg-secondary text-uppercase">
                          {{$estimate->status}}
                        </span>
                        @elseif ($estimate->status=='sent')
                        <span class="badge bg-primary text-uppercase">
                          {{$estimate->status}}
                        </span>
                        @elseif ($estimate->status=='accepted')
                        <span class="badge bg-success text-uppercase">
                          {{$estimate->status}}
                        </span>
                        @elseif ($estimate->status=='rejected')
                        <span class="badge bg-danger text-uppercase">
                          {{$estimate->status}}
                        </span>
                        @elseif ($estimate->status=='converted')
                        <span class="badge bg-info text-uppercase">
                          {{$estimate->status}}
                        </span>
                        @endif

                      </td>
                      <td>

                        <?php
                                              $grandTotal=$estimate->estimate_details()->sum('total');
                                              $grandTotal=($grandTotal-$estimate->discount)+$estimate->vat+$estimate->tax;
                                          ?>
                        {{$grandTotal}}
                      </td>
                      <td>
                        <a class="btn btn-outline-primary btn-sm" href="{{route('work_order_show',$estimate->id)}}"><i
                            class="fa-solid fa-laptop-file"></i></a>

                        <a class="btn btn-outline-primary btn-sm"
                          href="{{route('work_completion_show',$estimate->id)}}"><i
                            class="fa-solid fa-certificate"></i></a>
                      </td>

                      <td>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                          <a class="btn btn-outline-primary btn-sm" href="{{route('estimate.show',$estimate->id)}}"><i
                              class="fas fa-eye"></i></a>

                          <a class="btn btn-outline-primary btn-sm mx-2"
                            href="{{route('estimate.edit',$estimate->id)}}"><i class="fas fa-edit"></i></a>

                          <a href="#" class="btn btn-outline-danger btn-sm delete" delid="{{$estimate->id}}"><i
                              class="fas fa-trash"></i></a>
                        </div>
                        {{Form::open(['route'=>['estimate.destroy',$estimate->id],'method'=>'DELETE','id'=>'destroy_form_'.$estimate->id])}}
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