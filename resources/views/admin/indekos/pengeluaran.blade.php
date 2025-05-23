@extends('layouts.indekos-layout')
@section('title', 'Pengeluaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pengeluaran</h3>
                <div class="card-tools">
                    <form class="form-inline" method="GET" action="{{ route('pengeluaran.index', ['indekosId' => $indekos->id]) }}">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Cari nama...">
                        <input type="date" name="start_date" class="form-control mr-2" placeholder="Tanggal awal">
                        <input type="date" name="end_date" class="form-control mr-2" placeholder="Tanggal akhir">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        <button type="button" class="btn btn-danger ml-2" onclick="window.location='{{ route('pengeluaran.index', ['indekosId' => $indekos->id]) }}'">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button type="button" class="btn btn-primary float-right ml-2" data-bs-toggle="modal" data-bs-target="#tambahPengeluaranModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </form>
                    
                </div>
            </div>
            <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ route('pengeluaran.index', [
                                    'indekosId' => $indekos->id,
                                    'sort_by' => 'nama',
                                    'sort_direction' => request('sort_by') == 'nama' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'start_date' => request('start_date'),
                                    'end_date' => request('end_date')
                                ]) }}" class="text-dark">
                                    NAMA
                                    @if(request('sort_by') == 'nama')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('pengeluaran.index', [
                                    'indekosId' => $indekos->id,
                                    'sort_by' => 'jenis',
                                    'sort_direction' => request('sort_by') == 'jenis' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'start_date' => request('start_date'),
                                    'end_date' => request('end_date')
                                ]) }}" class="text-dark">
                                    Jenis
                                    @if(request('sort_by') == 'jenis')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('pengeluaran.index', [
                                    'indekosId' => $indekos->id,
                                    'sort_by' => 'tanggal',
                                    'sort_direction' => request('sort_by') == 'tanggal' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'start_date' => request('start_date'),
                                    'end_date' => request('end_date')
                                ]) }}" class="text-dark">
                                    Tanggal
                                    @if(request('sort_by') == 'tanggal')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('pengeluaran.index', [
                                    'indekosId' => $indekos->id,
                                    'sort_by' => 'jumlah_uang',
                                    'sort_direction' => request('sort_by') == 'jumlah_uang' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'start_date' => request('start_date'),
                                    'end_date' => request('end_date')
                                ]) }}" class="text-dark">
                                    Jumlah Uang
                                    @if(request('sort_by') == 'jumlah_uang')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengeluarans as $pengeluaran)
                        <tr>
                            <td>{{ $pengeluaran->nama }}</td>
                            <td>{{ $pengeluaran->jenis }}</td>
                            <td>{{ $pengeluaran->tanggal }}</td>
                            <td>{{ number_format($pengeluaran->jumlah_uang, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data pengeluaran</td>
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

<!-- Modal Tambah Pengeluaran -->
<div class="modal fade" id="tambahPengeluaranModal" tabindex="-1" aria-labelledby="tambahPengeluaranModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPengeluaranModalLabel">Tambah Pengeluaran Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pengeluaran.store', $indekos->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" min="2020-01-01" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_uang" class="form-label">Jumlah Uang</label>
                        <input type="number" class="form-control" id="jumlah_uang" name="jumlah_uang" required>
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
@endsection
