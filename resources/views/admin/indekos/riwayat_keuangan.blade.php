@extends('layouts.indekos-layout')

@section('title', 'Riwayat Keuangan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pemasukan</h3>
                <div class="card-tools">
                    <form class="form-inline" method="GET" action="{{ route('riwayat_keuangan.index', ['indekos' => $indekos->id]) }}">
                        <input type="text" name="search" class="form-control mr-2" placeholder="cari nama...">
                        <input type="date" name="start_date" class="form-control mr-2" placeholder="tanggal awal">
                        <input type="date" name="end_date" class="form-control mr-2" placeholder="tanggal akhir">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        <button type="button" class="btn btn-danger ml-2" onclick="window.location='{{ route('riwayat_keuangan.index', ['indekos' => $indekos->id]) }}'">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button type="button" class="btn btn-success ml-2" onclick="window.location='{{ route('riwayat-keuangan.export', ['indekosId' => $indekos->id]) }}'">
                            <i class="fas fa-file-excel"></i>
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
                <div class="d-flex justify-content-center text-center mb-3">
                    <div class="mr-3"><strong>Total Pemasukan: </strong>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
                    <div class="mr-3"><strong>Total Pengeluaran: </strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                    <div><strong>Total Jumlah Uang: </strong>Rp {{ number_format($totalJumlahUang, 0, ',', '.') }}</div>
                </div>
                <table class="table table-bordered">
                <thead>
                        <tr>
                            <th>
                                <a href="{{ route('riwayat_keuangan.index', [
                                    'indekos' => $indekos->id,
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
                                <a href="{{ route('riwayat_keuangan.index', [
                                    'indekos' => $indekos->id,
                                    'sort_by' => 'no_kamar',
                                    'sort_direction' => request('sort_by') == 'no_kamar' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'start_date' => request('start_date'),
                                    'end_date' => request('end_date')
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
                                <a href="{{ route('riwayat_keuangan.index', [
                                    'indekos' => $indekos->id,
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
                                <a href="{{ route('riwayat_keuangan.index', [
                                    'indekos' => $indekos->id,
                                    'sort_by' => 'tanggal_bayar',
                                    'sort_direction' => request('sort_by') == 'tanggal_bayar' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'start_date' => request('start_date'),
                                    'end_date' => request('end_date')
                                ]) }}" class="text-dark">
                                    Tanggal Bayar
                                    @if(request('sort_by') == 'tanggal_bayar')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('riwayat_keuangan.index', [
                                    'indekos' => $indekos->id,
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
                            <th>
                                <a href="{{ route('riwayat_keuangan.index', [
                                    'indekos' => $indekos->id,
                                    'sort_by' => 'status',
                                    'sort_direction' => request('sort_by') == 'status' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                    'search' => request('search'),
                                    'start_date' => request('start_date'),
                                    'end_date' => request('end_date')
                                ]) }}" class="text-dark">
                                    Status
                                    @if(request('sort_by') == 'status')
                                        <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayats as $riwayat)
                        <tr>
                            <td>{{ $riwayat['nama'] }}</td>
                            <td>{{ $riwayat['no_kamar'] }}</td>
                            <td>{{ $riwayat['jenis'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($riwayat['tanggal_bayar'])->format('d-m-Y') }}</td>
                            <td>{{ number_format($riwayat['jumlah_uang'], 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-success">
                                    Selesai
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data riwayat keuangan</td>
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
@endsection
