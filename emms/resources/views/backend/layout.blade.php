<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Easy Microcredit Management System</title>
    <link rel="icon" type="image/x-icon" href="{{asset('img/mcl.png')}}">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/fontawesome.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <!--date picker-->
    <link rel="stylesheet" href="{{asset('plugins/datetimepicker-master/jquery.datetimepicker.min.css')}}">

    <!-- custom css -->
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
    <!-- Navbar -->
    @include('backend.inc.navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @if(Auth::user()->is_admin)
        @include('backend.inc.sidebar')
    @else
        @include('backend.inc.profile_sidebar')
    @endif
    

    <!-- Content Wrapper. Contains page content -->

    @include('sweetalert::alert')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- /.content-header -->

        <!-- Main content -->
        @yield('content')

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    @include('backend.inc.footer')
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!--date picker-->
<script src="{{asset('plugins/datetimepicker-master/jquery.datetimepicker.full.js')}}"></script>

<!-- overlayScrollbars -->
<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<!-- PAGE SCRIPTS -->
<script src="{{asset('dist/js/pages/dashboard2.js')}}"></script>
<!--custom js-->
<script src="{{asset('js/custom.js')}}"></script>
</body>
</html>

<script>

    $('.datetime').datetimepicker({
        format: 'Y-m-d',
    });

</script>

@stack('scripts')
