<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link">
        <!-- <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="brand-image"> -->
        <span class="brand-text font-weight-bold">Pengelolaan Indekos</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('kelolaakun') }}" class="nav-link {{ Request::is('kelolaakun') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Akun</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('indekos*') || Request::is('fasilitas*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('indekos*') || Request::is('fasilitas*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Indekos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('indekos.index') }}" class="nav-link {{ Request::is('indekos*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kelola Indekos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('fasilitas.index') }}" class="nav-link {{ Request::is('fasilitas*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fasilitas</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview {{ Request::is('admin/pengaduan*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('admin/pengaduan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exclamation-circle"></i>
                        <p>
                            Pengaduan Kamar
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('admin.pengaduan')}}" class="nav-link {{ Request::is('admin/pengaduan/laporan') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laporan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('riwayat.pengaduan')}}" class="nav-link {{ Request::is('admin/pengaduan/riwayat') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Riwayat Laporan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- <li class="nav-item">
                    <a href="{{ route('admin.notifications') }}" class="nav-link {{ Request::is('admin/notifications') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>Notifikasi</p>
                    </a>
                </li> -->
            </ul>
        </nav>
    </div>
</aside>

<!-- <style>
    .brand-image {
        max-height: 40px !important; /* Sesuaikan ukuran logo */
        /* margin-right: 10px; */
    }
</style> -->

<style>
    .brand-link {
        display: flex;
        justify-content: center; /* Memposisikan di tengah secara horizontal */
        align-items: center; /* Memposisikan di tengah secara vertikal */
    }
</style>