<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-center">
        <span class="brand-text font-weight-bold">
            {{ Auth::user()->kamar && Auth::user()->kamar->indekos ? Auth::user()->kamar->indekos->nama : 'Tidak Diketahui' }}
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('user.dashboard') }}" class="nav-link {{ Request::is('user/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.pembayaran')}}" class="nav-link {{ Request::is('user/pembayaran') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Pembayaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.pengaduan')}}" class="nav-link {{ Request::is('user/pengadu') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exclamation-circle"></i>
                        <p>Pengaduan Kamar</p>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="{{ route('user.akun') }}" class="nav-link {{ Request::is('user/akun') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Akun</p>
                    </a>
                </li> -->
            </ul>
        </nav>
    </div>
</aside>

