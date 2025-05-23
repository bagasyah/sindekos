<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- AdminLTE CSS dari file lokal -->
    <link rel="stylesheet" href="{{ asset('admin-lte/dist/css/adminlte.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="hold-transition sidebar-mini">
    <!-- Loading Overlay -->
    <div id="loading-overlay">
        <div class="loading-icon">
            <i class="fas fa-bed"></i>
        </div>
    </div>

    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-custom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifikasi -->
                <li class="nav-item">
                    <a href="{{ route('admin.notifications') }}" class="nav-link">
                        <i class="fas fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                    </a>
                </li>
                @auth
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 text-danger">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                </li>
                @endauth
            </ul>
        </nav>

        @include('partials.admin-sidebar')

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <h1 class="m-0 text-dark">@yield('page_title')</h1>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2023 Admin Panel.</strong> Hak Cipta Dilindungi.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE JS dari file lokal -->
    <script src="{{ asset('admin-lte/dist/js/adminlte.min.js') }}"></script>
    <!-- Import file JavaScript baru -->
    <script src="{{ asset('js/admin.js') }}" defer></script>
    @yield('additional_js')
</body>
</html>
