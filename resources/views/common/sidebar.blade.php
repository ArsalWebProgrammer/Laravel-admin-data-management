<head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon -->
    <link href="{{ asset('img/logo/logo.png') }}" rel="icon">
    
    <title>Arwa Admin Dashboard</title>
    
    <!-- Font Awesome Icons -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/ruang-admin.min.css') }}" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
</head>

  <style>
                  @media only screen and (max-width: 767px){
                    .btncustom{margin-top: -36px;}
                  }
                  
                  
                </style>
                
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon">
        <img src="{{ asset('img/logo/logo.png') }}" alt="Logo">
        </div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('monthlyRevenue.page') }}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <hr class="sidebar-divider">
          <div class="sidebar-heading">
        Money Management
      </div>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('management') }}" aria-controls="collapseBootstrap">
        <i class="fa">&#xf0d6;</i>
          <span>Salary</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('d-expenses') }}" aria-controls="collapseBootstrap">
        <i class="fas fa-book"></i>
          <span>Daily Expense</span>
        </a>
      </li>

      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Data Tables
      </div>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}" aria-controls="collapseBootstrap">
          <i class="fa fa-list"></i>
          <span>All Users</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('clients.index') }}" >
          <i class="fas fa-fw fa-users"></i>
          <span>All Clients</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('all-orders') }}" >
          <i class="fas fa-fw fa-table"></i>
          <span>All Orders</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('addOrderForm') }}" aria-controls="collapseBootstrap">
          <i class="far fa-fw fa-window-maximize"></i>
          <span>Add New Order</span>
        </a>
        
      </li>
     
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        User
      </div>
     
@if (Auth::check())
            @if (Auth::user()->type === 'Admin') 
            <li class="nav-item">
            <a class="nav-link" href="{{route('register')}}">
                <i class="fas fa-fw fa-user"></i>
                <span>Register</span>
            </a>
        </li>
        
            @else

            @endif 
            @else

            @endif 
            <li class="nav-item">
    @if (Auth::check())
        <form method="POST" action="{{ route('logout') }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to logout?');">
            @csrf
            <button type="submit" class="nav-link" style="border: none; background: none; color: inherit;">
                <i class="fas fa-fw fa-user"></i>
                <span>Logout</span>
            </button>
        </form>
                </li>
    @else
    <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>Login</span>
        </a>
    @endif
</li>

      <hr class="sidebar-divider">
      <div class="version" id="version-ruangadmin"></div>
    </ul>
    <!-- Sidebar End -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
          <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <ul class="navbar-nav ml-auto">
          <li class="nav-item ">
<a href="{{ route('addOrderForm') }}" class="btn btn-primary" style="background-color: white; color: #272727; margin-top: 22px;">Add New Order</a>
</li> 
        
            <div class="topbar-divider d-none d-sm-block"></div>
            <li class="nav-item dropdown no-arrow">
               <!-- <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="false" aria-expanded="false"> -->
                
              <div class="form-group col-lg-10 col-md-6 ">
              <a class="nav-link " href="#" >
                <img class="img-profile rounded-circle " src="{{ asset('img/boy.png') }}" alt="Logo" style="max-width: 60px">
                <span class="ml-2 d-none d-lg-inline text-white small"> @if (Auth::check())
                Hello, {{ Auth::user()->name }}!
            @else
                Hello, Guest!
            @endif</span>
              </a> 
</div>
              
              
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </nav>
        <!-- Topbar -->
   