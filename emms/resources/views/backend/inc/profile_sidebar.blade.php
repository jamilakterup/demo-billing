<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/')}}" class="brand-link">
        <img src="{{asset('img/mcl.png')}}" width="30px">
        <span title="Easy Microcredit Management System" class="brand-text font-weight-bold text-center" style="letter-spacing: 2px">EMCMS</span>
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
                    <a class="nav-link" href="{{route('profile.index',['user_id'=>Auth::user()->id])}}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link" href="{{route('profile.member.index',['user_id'=>Auth::user()->id])}}">
                        <i class="nav-icon fas fa-users-cog"></i>

                        Members
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('profileLoan.index',['user_id'=>Auth::user()->id])}}">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        Loan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('profileInstalment.index',['user_id'=>Auth::user()->id])}}">
                        <i class="nav-icon fas fa-box-usd"></i>
                        Instalment
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('profileSavings.index',['user_id'=>Auth::user()->id])}}">
                        <i class="nav-icon fas fa-sack-dollar"></i>
                        Savings
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('profileWithdrawal.index',['user_id'=>Auth::user()->id])}}">
                        <i class="nav-icon fas fa-hands-usd"></i>
                        Withdrawal
                    </a>
                </li>

                
        </nav>

        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>