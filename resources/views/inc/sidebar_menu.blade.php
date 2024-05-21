@php
$current_route=Route::currentRouteName();

$productManagement=false;
$userRoleManagement=false;
$employeeManagement=false;
$invoice=false;
$payment=false;
$estimate=false;
$designation=false;
$employee=false;
$user=false;
$module=false;
$permission=false;
$role=false;
$project=false;
$lead=false;
$userProfile=false;
$userPayment=false;
$serviceCollection=false;
$cpLead=false;
$agreement=false;
$soldLead=false;
$inactiveLead=false;

$route_convert_array=explode('.',$current_route);

if($current_route=='product' || $current_route=='productType' || $current_route=='unit'){
$productManagement=true;
}
if($current_route=='invoice.index'){
$invoice=true;
}
if($current_route=='payment'){
$payment=true;
}
if($route_convert_array[0]=='estimate'){
$estimate=true;
}
if($route_convert_array[0]=='agreement'){
$agreement=true;
}

if($route_convert_array[0]=='role'){
$role=true;
$userRoleManagement=true;
}
if($current_route=='module'){
$module=true;
$userRoleManagement=true;
}
if($current_route=='permission'){
$permission=true;
$userRoleManagement=true;
}
if($route_convert_array[0]=='user'){
$user=true;
$userRoleManagement=true;
}

if($current_route=='designation' || $current_route=='employee'){
$employeeManagement=true;
}

if($current_route=='designation'){
$designation=true;
}
if($current_route=='employee'){
$employee=true;
}
if($current_route=='project'){
$project=true;
}
if($current_route=='lead_collection'){
$lead=true;
}


// user route
if($current_route=='profile'){
$userProfile=true;
}
if($current_route=='payment'){
$userPayment=true;
}
if($current_route=='service_lead'){
$serviceCollection=true;
}
if($current_route=='cp_lead'){
$cpLead=true;
}
if($current_route=='sold_lead'){
$soldLead=true;
}
if($current_route=='inactive_lead'){
$inactiveLead=true;
}

@endphp
{{-- @if (Auth::user()->hasRole('admin'))
<li class="nav-item ">
  <a href="/" class="nav-link {{$current_route=='home'?'active':''}}">
    <i class="nav-icon fas fa-tachometer-alt"></i>
    <p>
      Dashboard
    </p>
  </a>
</li>
@elseif(Auth::user()->hasRole('user'))
<li class="nav-item ">
  <a href="/" class="nav-link {{$current_route=='home'?'active':''}}">
    <i class="nav-icon fas fa-tachometer-alt"></i>
    <p>
      User Dashboard
    </p>
  </a>
</li>
@endif --}}
<!-- Sidebar Menu -->

@if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('editor'))
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->


    <li class="nav-item ">
      <a href="/" class="nav-link {{$current_route=='home'?'active':''}}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
          Dashboard
          {{-- <span class="right badge badge-danger">New</span> --}}
        </p>
      </a>
    </li>

    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="nav-icon fas fa-cog"></i>

        <p>
          Setting
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{route('organization')}}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Organization</p>
          </a>
        </li>


        <li class="nav-item">
          <a href="{{route('invoiceType.index')}}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Invoice Type</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{route('quotationType.index')}}" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Quotation Type</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Email</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>SMS</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/status" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>Status</p>
          </a>
        </li>

      </ul>
    </li>

    <li class="nav-item {{$userRoleManagement?'menu-open':''}}">
      <a href="#" class="nav-link {{$userRoleManagement?'active':''}}">
        <i class="fas fa-user-circle nav-icon"></i>

        <p>
          User Role Management
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{route('user.index')}}" class="nav-link {{$user?'active':''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>User</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('module')}}" class="nav-link {{$module?'active':''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>Module</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('permission')}}" class="nav-link {{$permission?'active':''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>Permission</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('role.index')}}" class="nav-link {{$role?'active':''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>Role</p>
          </a>
        </li>

      </ul>
    </li>

    <li class="nav-item">
      <a href="{{route('customer.index')}}" class="nav-link">
        <i class="nav-icon fas fa-portrait"></i>
        <p>Customer</p>
      </a>
    </li>

    <li class="nav-item {{$employeeManagement?'menu-open':''}}">
      <a href="#" class="nav-link {{$employeeManagement?'active':''}}">
        <i class="fas fa-user-tie nav-icon"></i>
        <p>
          Employee Management
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">


        <li class="nav-item">
          <a href="{{route('designation')}}" class="nav-link {{$current_route=='designation'?'active':''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>Designation</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('employee')}}" class="nav-link {{$current_route=='employee'?'active':''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>All Employee</p>
          </a>
        </li>

      </ul>
    </li>

    <li class="nav-item {{$productManagement?'menu-open':''}}">
      <a href="#" class="nav-link {{$productManagement?'active':''}}">
        <i class="fas fa-box nav-icon"></i>

        <p>
          Product Management
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">

        <li class="nav-item">
          <a href="{{route('unit')}}" class="nav-link {{$current_route=='unit'?'active':''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>Unit</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('productType')}}" class="nav-link {{$current_route=='productType'?'active':''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>Type</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{route('product')}}" class="nav-link {{$current_route=='product'?'active':''}}">
            <i class="far fa-circle nav-icon"></i>
            <p>Product</p>
          </a>
        </li>

      </ul>
    </li>

    <li class="nav-item">
      <a href="{{route('estimate.index')}}" class="nav-link {{$estimate?'active':''}}">
        <i class="nav-icon fas fa-file-invoice"></i>
        <p>Quotation</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{route('agreement.index')}}" class="nav-link {{$agreement?'active':''}}">
        <i class="nav-icon fa-solid fa-file-contract"></i>
        <p>Agreement</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{route('invoice.index')}}" class="nav-link {{$invoice?'active':''}}">
        <i class="nav-icon fas fas fa-receipt"></i>
        <p>Invoice</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{route('payment')}}" class="nav-link {{$payment?'active':''}}">
        <i class="nav-icon fas fa-credit-card"></i>
        <p>Payment</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{route('project')}}" class="nav-link {{$project?'active':''}}">
        <i class="nav-icon fa fa-tasks" aria-hidden="true"></i>
        <p>Projects</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{route('lead_collection')}}" class="nav-link {{$lead?'active':''}}">
        <i class="nav-icon fa-solid fa-people-group"></i>
        <p>New Lead</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{route('service_lead')}}" class="nav-link {{$serviceCollection?'active':''}}">
        <i class="nav-icon fa-solid fa-bell-concierge"></i>
        <p>Service Lead</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{route('cp_lead')}}" class="nav-link {{$cpLead?'active':''}}">
        <i class="nav-icon fa-solid fa-person-rays"></i>
        <p>CP Lead</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{route('sold_lead')}}" class="nav-link {{$soldLead?'active':''}}">
        <i class="nav-icon fa-solid fa-hand-holding-dollar"></i>
        <p>Sold Lead</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{route('inactive_lead')}}" class="nav-link {{$inactiveLead?'active':''}}">
        <i class="nav-icon fa-solid fa-ban"></i>
        <p>Inactive Lead</p>
      </a>
    </li>
    {{-- <li class="nav-item">
      <a href="{{route('inactive_lead')}}" class="nav-link {{$inactiveLead?'active':''}}">
        <i class="nav-icon fa-solid fa-person-rays"></i>
        <p>Inactive Lead</p>
      </a>
    </li> --}}

  </ul>
</nav>

@elseif(Auth::user()->hasRole('user')==true)
<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->


    <li class="nav-item ">
      <a href="/" class="nav-link {{$current_route=='home'?'active':''}}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
          Dashboard
          {{-- <span class="right badge badge-danger">New</span> --}}
        </p>
      </a>
    </li>

    <li class="nav-item ">
      <a href="/user/profile" class="nav-link {{$userProfile?'active':''}}">
        <i class="nav-icon fa-solid fa-user"></i>
        <p>
          Profile
          {{-- <span class="right badge badge-danger">New</span> --}}
        </p>
      </a>
    </li>

    <li class="nav-item ">
      <a href="/payment" class="nav-link {{$userPayment?'active':''}}">
        <i class="nav-icon fas fa-money-check-alt"></i>
        <p>
          Payment
          {{-- <span class="right badge badge-danger">New</span> --}}
        </p>
      </a>
    </li>

  </ul>
</nav>

@endif

<!-- /.sidebar-menu -->