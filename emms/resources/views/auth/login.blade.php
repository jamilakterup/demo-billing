
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>AdminLTE 3 | Dashboard 2</title>

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

    <style>
        .main{
            background:#f4f6f9;
            position:relative;
            width:100vw;
            height:100vh;
        }

        .form-body{
            position:absolute;
            left:50%;
            top:50%;
            transform:translate(-50%,-50%);
             width:300px;
        }
        .form-body form{
            background:white;
            padding:40px;
           
            
            border-radius:15px;
            
            box-shadow: 0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.2);
        }
        @media only screen and (max-width: 350px) {
           .form-body{
               width:90%;
           }
            }
    </style>
</head>
<body>



<div class="main">

    <div class="form-body">
    <h5 class="text-center mb-4 text-muted">দোয়েল সমবায় সমিতি</h5>
    <form method="POST" action="{{ route('login') }}">
                        @csrf

        <div class="form-group">

            <div class="col-md-12">
                <input id="email" placeholder="Email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group">

            <div class="col-md-12">
                <input id="password" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        

        <div class="form-group row mb-0">
            <div class="col-8 offset-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Login') }}
                </button>
            </div>
        </div>
    </form>
    </div>
</div>



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
