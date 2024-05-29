<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>rajIT Billing Management System</title>
  <link rel="icon" href="{{asset('/logo/nbelogo.png')}}" type="image/png" sizes="16x16">
  <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')}}">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

  <link rel="stylesheet" href="{{asset('plugins/sweetalert2/sweetalert2.min.css')}}">
  <!-- select2 -->
  <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/custom.css')}}">
  @yield('style')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  @include('sweetalert::alert')
  <div class="wrapper">

    <!-- Preloader -->
    {{-- <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="{{asset('logo/nbelogo.png')}}" alt="rajIT Solution Ltd." height="60"
        width="60">
    </div> --}}

    @include('inc.navbar')

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="/" class="brand-link">
        <img src="{{asset('logo/nbelogo.png')}}" alt="raj IT Logo" class="brand-image elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-bold">NBE MS</span>
      </a>

      <!-- Sidebar TODO-->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            @if (isset(Auth::user()->profile_photo_path))
            <img src="{{asset('photo/'.Auth::user()->profile_photo_path)}}" class="img-circle elevation-2"
              alt="User Image">
            @else
            <img src="{{asset('photo/dummy-user.png')}}" class="img-circle elevation-2" alt="User Image">
            @endif
          </div>
          <div class="info">
            <a href="#" class="d-block">{{Auth::user()->name}}</a>
          </div>

        </div> --}}


        @include('inc.sidebar_menu')
      </div>
      <!-- /.sidebar -->
    </aside>

    @yield('content')


    @include('inc.footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- ChartJS -->
  <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
  <!-- Sparkline -->
  <script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
  <!-- JQVMap -->
  <script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
  <script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
  <!-- daterangepicker -->
  <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
  <!-- Summernote -->
  <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
  <!-- overlayScrollbars -->
  <script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('dist/js/adminlte.js')}}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{asset('dist/js/demo.js')}}"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{asset('dist/js/pages/dashboard.js')}}"></script>

  <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
  <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
  <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
  <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
  <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
  <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
  <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <!-- Select2 -->
  <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
  <!-- date-range-picker -->
  <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
  @yield('js')
  @stack('scripts')
  <script>
    $(function () {
      $("#example1").DataTable({
        "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });

  $(function () {
      $("#example2").DataTable({
        "lengthChange": false, "autoWidth": false,
        "columnDefs":[{"orderable": false, "targets": [2] }],
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

  });


    $(document).ready(function(){


            $(document).on('click','.delete',function(e){
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
                $('#destroy_form_'+delid).submit();
                //window.location.href=route;
            }

            });

          });

        $(".select2").select2({
            theme: 'bootstrap4',
        });

        $('.date').daterangepicker({
            singleDatePicker: true,
            format: 'YYYY-MM-DD',
            minYear: 2011,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD',
            },
            calender_style: "picker_3"
        });


    });


function load_tomselect() {
    document.querySelectorAll(".tom-select").forEach(el => {
        if (el && el instanceof Element && el.tagName === "SELECT") {
            el.style.display = "";
            if (!el.hasAttribute("data-tomselect-initialized")) {
                let settings = { sortField: false };
                if (el.multiple) {
                    settings.plugins = ["remove_button", "clear_button"];
                } else {
                    settings.plugins = ["clear_button"];
                }
                if (el._tomselect) {
                    let selectedValue = el._tomselect.getValue();
                    el._tomselect.destroy();
                    el._tomselect = null;
                    el.value = selectedValue;
                }
                el._tomselect = new TomSelect(el, settings);
                el.setAttribute("data-tomselect-initialized", "true");
            }
        }
    });
}

load_tomselect();



document.addEventListener("DOMContentLoaded", () => {
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('message.processed', (message, component) => {
           load_tomselect();
        });
    }
});


  </script>

</body>

</html>