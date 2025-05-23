<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kamar - {{ $indekos->nama }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-color: #34495e;
            --light-color: #ecf0f1;
        }
        .navbar-custom {
            background-color: var(--primary-color);
        }
        .navbar-custom .nav-link, .navbar-custom .navbar-brand {
            color: var(--light-color) !important;
        }
        .sidebar-dark-primary {
            background-color: var(--dark-color);
        }
        .content-wrapper {
            background-color: var(--light-color);
        }
        .card {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }
        .loading-icon {
            font-size: 50px;
            color: var(--primary-color);
            animation: bounce 0.6s infinite alternate;
        }
        @keyframes bounce {
            from {
                transform: translateY(0px);
            }
            to {
                transform: translateY(-15px);
            }
        }
    </style>
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
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item dropdown">
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

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-bold">Panel Pemilik</span>
            </a>
            <div class="sidebar">
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
                                    <a href="{{ route('pengeluaran.index', $indekos->id) }}" class="nav-link {{ Request::is('indekos/*/laporan-keuangan/pengeluaran') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pengeluaran</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('riwayat_keuangan.index', $indekos->id) }}" class="nav-link {{ Request::is('indekos/*/laporan-keuangan/riwayat') ? 'active' : '' }}">
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

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <h1 class="m-0 text-dark">Daftar Kamar - {{ $indekos->nama }}</h1>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Daftar Kamar - {{ $indekos->nama }}</h3>
                                    <div class="card-tools">
                                        <form class="form-inline" method="GET" action="{{ route('kamar.index', ['indekosId' => $indekos->id]) }}">
                                            <input type="text" name="search" class="form-control mr-2" placeholder="Cari nomor kamar..." value="{{ request('search') }}">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            <button type="button" class="btn btn-danger ml-2" onclick="window.location='{{ route('kamar.index', ['indekosId' => $indekos->id]) }}'">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary float-right ml-2" data-bs-toggle="modal" data-bs-target="#tambahKamarModal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                <a href="{{ route('kamar.index', [
                                                    'indekosId' => $indekos->id,
                                                    'sort_by' => 'no_kamar',
                                                    'sort_direction' => request('sort_by') == 'no_kamar' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                                    'search' => request('search')
                                                ]) }}" class="text-dark">
                                                    No Kamar
                                                    @if(request('sort_by') == 'no_kamar')
                                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                <a href="{{ route('kamar.index', [
                                                    'indekosId' => $indekos->id,
                                                    'sort_by' => 'harga',
                                                    'sort_direction' => request('sort_by') == 'harga' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                                    'search' => request('search')
                                                ]) }}" class="text-dark">
                                                    Harga
                                                    @if(request('sort_by') == 'harga')
                                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                <a href="{{ route('kamar.index', [
                                                    'indekosId' => $indekos->id,
                                                    'sort_by' => 'status',
                                                    'sort_direction' => request('sort_by') == 'status' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                                    'search' => request('search')
                                                ]) }}" class="text-dark">
                                                    Status
                                                    @if(request('sort_by') == 'status')
                                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Fasilitas</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kamars as $kamar)
                                        <tr>
                                            <td>{{ $kamar->no_kamar }}</td>
                                            <td>Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $kamar->status == 'Terisi' ? 'bg-danger' : 'bg-success' }}">
                                                    {{ $kamar->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $fasilitasIds = explode(',', $kamar->fasilitas_id);
                                                    $fasilitasNames = [];
                                                    foreach($fasilitasIds as $id) {
                                                        $fasilitasItem = $fasilitas->firstWhere('id', $id);
                                                        if ($fasilitasItem) {
                                                            $fasilitasNames[] = $fasilitasItem->nama_fasilitas;
                                                        }
                                                    }
                                                    echo implode(', ', $fasilitasNames);
                                                @endphp
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm edit-btn"
                                                    data-id="{{ $kamar->id }}"
                                                    data-no_kamar="{{ $kamar->no_kamar }}"
                                                    data-harga="{{ $kamar->harga }}"
                                                    data-fasilitas="{{ $kamar->fasilitas_id }}"
                                                    data-status="{{ $kamar->status }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data kamar</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                @push('scripts')
                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Menambahkan event listener untuk semua link pengurutan
                                    const sortLinks = document.querySelectorAll('th a');
                                    sortLinks.forEach(link => {
                                        link.addEventListener('click', function(e) {
                                            e.preventDefault();
                                            window.location.href = this.href;
                                        });
                                    });
                                });
                                </script>
                                @endpush
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Tambah Kamar -->
                    <div class="modal fade" id="tambahKamarModal" tabindex="-1" aria-labelledby="tambahKamarModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="tambahKamarModalLabel">Tambah Kamar Baru</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('kamar.store', ['indekosId' => $indekos->id]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="no_kamar" class="form-label">Nomor Kamar</label>
                                            <input type="text" class="form-control @error('no_kamar') is-invalid @enderror" id="no_kamar" name="no_kamar" value="{{ old('no_kamar') }}" required pattern="\d*" title="Nomor kamar hanya boleh berisi angka">
                                            @error('no_kamar')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <span class="error-message" id="no_kamar-error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="harga" class="form-label">Harga</label>
                                            <input type="text" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga') }}" required>
                                            @error('harga')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="fasilitas_id" class="form-label">Fasilitas</label>
                                            <select class="form-control @error('fasilitas_id') is-invalid @enderror" id="fasilitas_id" name="fasilitas_id[]" multiple="multiple" required>
                                                @foreach ($fasilitas as $item)
                                                    <option value="{{ $item->id }}" {{ in_array($item->id, old('fasilitas_id', [])) ? 'selected' : '' }}>
                                                        {{ $item->nama_fasilitas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('fasilitas_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Edit Kamar -->
                    <div class="modal fade" id="editKamarModal" tabindex="-1" aria-labelledby="editKamarModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editKamarModalLabel">Edit Kamar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="editKamarForm" method="POST" action>
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="edit_no_kamar" class="form-label">Nomor Kamar</label>
                                            <input type="text" class="form-control" id="edit_no_kamar" name="no_kamar" required>
                                            <span class="error-message" id="edit_no_kamar-error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_harga" class="form-label">Harga</label>
                                            <input type="text" class="form-control" id="edit_harga" name="harga" required>
                                            <span class="error-message" id="edit_harga-error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_fasilitas_id" class="form-label">Fasilitas</label>
                                            <select class="form-control" id="edit_fasilitas_id" name="fasilitas_id[]" multiple="multiple">
                                                @foreach ($fasilitas as $item)
                                                    <option value="{{ $item->id }}" 
                                                        {{ old('fasilitas_id') && in_array($item->id, old('fasilitas_id')) ? 'selected' : '' }}>
                                                        {{ $item->nama_fasilitas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="edit_status" class="form-label">Status</label>
                                            <select class="form-control" id="edit_status" name="status" required disabled>
                                                <option value="Terisi">Terisi</option>
                                                <option value="Tidak Terisi">Tidak Terisi</option>
                                            </select>
                                        </div>
                                        <input type="hidden" id="edit_status_hidden" name="status" value="">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2023 Panel Pemilik.</strong> Hak Cipta Dilindungi.
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        $(document).ready(function() {
            // Ambil nomor kamar yang sudah ada
            var existingRoomNumbers = [];
            @foreach($kamars as $kamar)
                existingRoomNumbers.push(parseInt('{{ $kamar->no_kamar }}'));
            @endforeach

            // Cari nomor kamar tertinggi
            var highestRoomNumber = Math.max(...existingRoomNumbers, 0);

            // Validasi input nomor kamar untuk form tambah
            $('#no_kamar').on('input', function() {
                var inputValue = parseInt($(this).val());
                var errorMessage = '';

                if (isNaN(inputValue)) {
                    errorMessage = 'Nomor kamar harus berupa angka';
                } else if (inputValue <= 0) {
                    errorMessage = 'Nomor kamar harus lebih besar dari 0';
                } else if (existingRoomNumbers.includes(inputValue)) {
                    errorMessage = 'Nomor kamar sudah digunakan';
                } else if (inputValue > highestRoomNumber + 1) {
                    errorMessage = 'Nomor kamar tidak boleh melompat jauh dari nomor kamar tertinggi (' + highestRoomNumber + ')';
                }

                $('#no_kamar-error').text(errorMessage);
                if (errorMessage) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Validasi input nomor kamar untuk form edit
            $('#edit_no_kamar').on('input', function() {
                var inputValue = parseInt($(this).val());
                var errorMessage = '';
                var currentStatus = $('#edit_status').val();

                if (currentStatus === 'Terisi') {
                    $(this).prop('readonly', true);
                    errorMessage = 'Nomor kamar tidak dapat diubah karena kamar sedang terisi';
                } else {
                    $(this).prop('readonly', false);
                    if (isNaN(inputValue)) {
                        errorMessage = 'Nomor kamar harus berupa angka';
                    } else if (inputValue <= 0) {
                        errorMessage = 'Nomor kamar harus lebih besar dari 0';
                    } else if (existingRoomNumbers.includes(inputValue) && inputValue !== parseInt($(this).data('original'))) {
                        errorMessage = 'Nomor kamar sudah digunakan';
                    } else if (inputValue > highestRoomNumber + 1) {
                        errorMessage = 'Nomor kamar tidak boleh melompat jauh dari nomor kamar tertinggi (' + highestRoomNumber + ')';
                    }
                }

                $('#edit_no_kamar-error').text(errorMessage);
                if (errorMessage) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            $('#harga').on('keyup', function() {
                $(this).val(formatRupiah(this.value));
            });

            $('#fasilitas_id').select2({
                tags: true,
                placeholder: 'Pilih atau ketik fasilitas',
                allowClear: true,
                ajax: {
                    url: "{{ route('get-category') }}",
                    type: "POST",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            name: params.term,
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.nama_fasilitas
                                };
                            })
                        };
                    },
                }
            });

            // Initialize Select2 for edit modal
            $('#edit_fasilitas_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih fasilitas',
                allowClear: true
            });

            // Handle edit button click
            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');
                const noKamar = $(this).data('no_kamar');
                const harga = $(this).data('harga');
                let fasilitas = $(this).data('fasilitas');
                const status = $(this).data('status');

                // Pastikan fasilitas adalah array
                if (typeof fasilitas === 'string') {
                    fasilitas = fasilitas.split(',');
                } else if (typeof fasilitas === 'number') {
                    fasilitas = [fasilitas.toString()];
                } else {
                    fasilitas = [];
                }

                $('#edit_no_kamar').val(noKamar);
                $('#edit_no_kamar').data('original', noKamar); // Simpan nomor kamar asli
                $('#edit_harga').val(formatRupiah(harga.toString()));
                $('#edit_fasilitas_id').val(fasilitas).trigger('change');
                $('#edit_status').val(status);
                $('#edit_status_hidden').val(status);

                // Set readonly berdasarkan status
                if (status === 'Terisi') {
                    $('#edit_no_kamar').prop('readonly', true);
                } else {
                    $('#edit_no_kamar').prop('readonly', false);
                }

                $('#editKamarForm').attr('action', `/indekos/${{!! json_encode($indekos->id) !!}}/kamar/${id}`);
                $('#editKamarModal').modal('show');
            });

            // Validasi form tambah kamar
            $('#tambahKamarModal form').submit(function(e) {
                var isValid = true;
                $('.error-message').text('');

                // Validasi nomor kamar
                if ($('#no_kamar').val().trim() === '') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Nomor kamar wajib diisi'
                    });
                    isValid = false;
                } else if (!/^\d+$/.test($('#no_kamar').val().trim())) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Nomor kamar hanya boleh berisi angka'
                    });
                    isValid = false;
                }

                // Validasi harga
                if ($('#harga').val().trim() === '') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Harga wajib diisi'
                    });
                    isValid = false;
                }

                // Validasi fasilitas
                if ($('#fasilitas_id').val() === null || $('#fasilitas_id').val().length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Fasilitas wajib dipilih'
                    });
                    isValid = false;
                }
            });

            // Validasi form edit kamar
            $('#editKamarForm').submit(function(e) {
                var isValid = true;
                var errorMessage = '';

                // Validasi nomor kamar
                var noKamar = parseInt($('#edit_no_kamar').val());
                var currentStatus = $('#edit_status').val();

                if (currentStatus === 'Terisi') {
                    $('#edit_no_kamar').prop('readonly', true);
                } else {
                    if (isNaN(noKamar)) {
                        errorMessage = 'Nomor kamar harus berupa angka';
                        isValid = false;
                    } else if (noKamar <= 0) {
                        errorMessage = 'Nomor kamar harus lebih besar dari 0';
                        isValid = false;
                    } else if (existingRoomNumbers.includes(noKamar) && noKamar !== parseInt($('#edit_no_kamar').data('original'))) {
                        errorMessage = 'Nomor kamar sudah digunakan';
                        isValid = false;
                    } else if (noKamar > highestRoomNumber + 1) {
                        errorMessage = 'Nomor kamar tidak boleh lebih dari ' + (highestRoomNumber + 1);
                        isValid = false;
                    }
                }

                // Validasi harga
                if ($('#edit_harga').val().trim() === '') {
                    errorMessage = 'Harga wajib diisi';
                    isValid = false;
                } else if (!/^\d+$/.test($('#edit_harga').val().trim().replace(/\./g, ''))) {
                    errorMessage = 'Harga hanya boleh berisi angka';
                    isValid = false;
                }

                // Validasi fasilitas
                if ($('#edit_fasilitas_id').val() === null || $('#edit_fasilitas_id').val().length === 0) {
                    errorMessage = 'Fasilitas wajib dipilih';
                    isValid = false;
                }

                // Ubah harga ke angka sebelum submit
                let hargaVal = $('#edit_harga').val().replace(/[^0-9]/g, '');
                $('#edit_harga').val(hargaVal);

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage
                    });
                }
            });
        });
    </script>

    <!-- JavaScript untuk membuka modal jika ada kesalahan -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                var tambahKamarModal = new bootstrap.Modal(document.getElementById('tambahKamarModal'));
                tambahKamarModal.show();
            @endif
        });
    </script>
</body>
</html>
