<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-bold">Panel Pemilik</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('indekos.detail', $indekos->id) }}" class="nav-link {{ Request::is('indekos*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('penyewa.index', $indekos->id)}}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Penyewa</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('kamar.index', ['indekosId' => $indekos->id]) }}" class="nav-link {{ Request::is('indekos/*/kamar*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bed"></i>
                        <p>Data Kamar</p>
                    </a>
                </li>
                <li class="nav-item has-treeview {{ Request::is('indekos/*/laporan-keuangan*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('indekos/*/laporan-keuangan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>
                            Laporan Keuangan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pemasukan.index', $indekos->id) }}" class="nav-link {{ Request::is('indekos/*/laporan-keuangan/pemasukan') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pemasukan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pengeluaran.index', $indekos->id)}}" class="nav-link {{ Request::is('indekos/*/laporan-keuangan/pengeluaran') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pengeluaran</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('riwayat_keuangan.index',$indekos->id)}}" class="nav-link {{ Request::is('indekos/*/laporan-keuangan/riwayat') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Riwayat</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('indekos.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-arrow-left"></i>
                        <p>Kembali</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
