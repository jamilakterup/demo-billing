@extends('layouts.master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Invoice Details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Invoice</li>
            <li class="breadcrumb-item active">Details</li>
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
              <div class="card-title">Invoice Details</div>
              <div class="card-tools">
                <a class="btn btn-sm btn-primary" href="{{asset('/pdf/'.$invoice->file)}}" download><i
                    class="fas fa-cloud-download-alt"></i> Download</a>
                <a class="btn btn-sm btn-primary" href="{{route('invoice.send.mail',$invoice->id)}}"><i
                    class="fas fa-paper-plane"></i> Send Email ({{$send_mail_count}})</a>
                <a class="btn btn-sm btn-primary" href="{{url()->previous()}}"><i class="fas fa-sms"></i> Send SMS
                  (0)</a>

                <div class="btn-group">
                  <button class="btn btn-primary btn-sm" type="button">
                    More
                  </button>
                  <button type="button" class="btn btn-sm btn-primary dropdown-toggle dropdown-toggle-split"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">

                    <a class="dropdown-item" href="">Mark Accepted</a>

                    <a class="dropdown-item" href="">Mark Rejecred</a>

                    <a class="dropdown-item" href="#">Mark Sent</a>
                    {{-- <a class="dropdown-item" href="{{route('invoice.accepted',$invoice->id)}}">Mark Accepted</a>
                    --}}
                    {{-- <a class="dropdown-item" href="{{route('invoice.accepted',$invoice->id)}}">Mark Accepted</a>
                    --}}
                    {{-- <a class="dropdown-item" href="{{route('invoice.rejected',$invoice->id)}}">Mark Rejecred</a>
                    --}}
                    {{-- <a class="dropdown-item" href="#">Mark Sent</a> --}}
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="alert alert-light mb-2" role="alert">
                Status: <span class="text-capitalize">{{$invoice->status}}</span>
              </div>
              <div id="pdf-viewer-container" class="pdfobject" class="border rounded">
                <iframe src="{{asset('/pdf/'.$invoice->file)}}" style="overflow: auto; width: 100%; min-height: 500px;">

                </iframe>
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