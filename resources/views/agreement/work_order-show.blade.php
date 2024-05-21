@extends('layouts.master')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Wrapper. Contains page content -->
    <iframe src="{{asset('/storage/'.$work_order_pdf)}}" frameborder="0" height="500px" width="100%"></iframe>
</div>
<!-- /.content-wrapper -->




@push('scripts')

<script>

</script>
@endpush


@endsection