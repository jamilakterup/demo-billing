<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <span class="brand-text font-weight-light">Microcredit Management System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('img/users/'.Auth::user()->photo)}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->name}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/')}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('user.index')}}">
                        <i class="nav-icon fas fa-user-plus"></i>
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('member.index')}}">
                        <i class="nav-icon fas fa-users-cog"></i>

                        Members
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('loan.index')}}">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        Loan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('instalment.index')}}">
                        <i class="nav-icon fas fa-box-usd"></i>
                        Instalment
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('savings.index')}}">
                        <i class="nav-icon fas fa-sack-dollar"></i>
                        Savings
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('savings_withdrawal.index')}}">
                        <i class="nav-icon fas fa-hands-usd"></i>
                        Withdrawal
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Report
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{route('report.loan.create')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Loan report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('report.daily.create')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Instalment Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('report.savings.create')}}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Savings report</p>
                            </a>
                        </li>

                        {{-- <li class="nav-item">
                            <a href="./index.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User report</p>
                            </a>
                        </li> --}}

                        {{-- <li class="nav-item">
                            <a href="./index.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Members report</p>
                            </a>
                        </li> --}}
                    </ul>
                </li>

            </ul>
        </nav>

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>