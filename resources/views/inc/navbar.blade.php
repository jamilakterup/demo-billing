<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!--End Notifications Dropdown Menu -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                {{-- <i class="fas fa-user-circle fa-1x"></i> --}}
                @if (isset(Auth::user()->profile_photo_path))
                    <img src="{{ asset('photo/' . Auth::user()->profile_photo_path) }}" class="img-circle"
                        alt="User Image" width="25px">
                @else
                    <img src="{{ asset('photo/dummy-user.png') }}" class="img-circle" alt="User Image" width="25px">
                @endif

            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">{{ Auth::user()->name }}</span>
                <div class="dropdown-divider"></div>
                <a href="user/profile" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile

                </a>
                <div class="dropdown-divider"></div>

                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
           document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

            </div>
        </li>

    </ul>
</nav>
<!-- /.navbar -->
