@extends('layouts.admin') <!-- Pastikan Anda menggunakan layout admin yang sesuai -->
@section('title', 'Riwayat Pengaduan')

@section('page_title', 'Riwayat Pengaduan')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Pengaduan Kamar (Selesai)</h3>
                <div class="card-tools">
                    <form class="form-inline" method="GET" action="{{ route('riwayat.pengaduan') }}">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Cari nama...">
                        <input type="date" name="start_date" class="form-control mr-2" placeholder="Tanggal awal">
                        <input type="date" name="end_date" class="form-control mr-2" placeholder="Tanggal akhir">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        <button type="button" class="btn btn-danger ml-2" onclick="window.location='{{ route('riwayat.pengaduan') }}'">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button type="button" class="btn btn-success ml-2" onclick="window.location='{{ route('riwayat.pengaduan.export') }}'">
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
                <div class="table-responsive">
                <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ route('riwayat.pengaduan', ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="text-dark">
                                        Nama
                                        @if(request('sort') == 'name')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('riwayat.pengaduan', ['sort' => 'indekos', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="text-dark">
                                        Indekos
                                        @if(request('sort') == 'indekos')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('riwayat.pengaduan', ['sort' => 'no_kamar', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="text-dark">
                                        No Kamar
                                        @if(request('sort') == 'no_kamar')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('riwayat.pengaduan', ['sort' => 'tanggal_pelaporan', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="text-dark">
                                        Tanggal Pelaporan
                                        @if(request('sort') == 'tanggal_pelaporan')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('riwayat.pengaduan', ['sort' => 'tanggal_perbaikan', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="text-dark">
                                        Tanggal Perbaikan
                                        @if(request('sort') == 'tanggal_perbaikan')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('riwayat.pengaduan', ['sort' => 'masalah', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="text-dark">
                                        Masalah
                                        @if(request('sort') == 'masalah')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Status</th>
                                <th>Foto</th>
                                <th>Foto Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengaduan as $item)
                                @if ($item->status == 'Selesai') <!-- Hanya tampilkan yang statusnya Selesai -->
                                <tr>
                                    <td>{{ $item->user->name ?? '-' }}</td>
                                    <td>{{ $item->user->kamar->indekos->nama ?? '-' }}</td>
                                    <td>{{ $item->user->kamar->no_kamar ?? '-' }}</td>
                                    <td>{{ $item->tanggal_pelaporan }}</td>
                                    <td>{{ $item->tanggal_perbaikan ?? '-' }}</td>
                                    <td>{{ $item->masalah }}</td>
                                    <td>
                                        <span class="badge {{ $item->status == 'Selesai' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->status == 'Selesai' ? 'Selesai' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto" style="width: 150px; height: auto;" class="img-thumbnail" data-bs-toggle="modal" data-bs-target="#modalViewFoto" data-foto="{{ asset('storage/' . $item->foto) }}">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->foto_akhir)
                                            <img src="{{ asset('storage/' . $item->foto_akhir) }}" alt="Foto" style="width: 150px; height: auto;" class="img-thumbnail" data-bs-toggle="modal" data-bs-target="#modalViewFoto" data-foto="{{ asset('storage/' . $item->foto_akhir) }}">
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal View Foto -->
<div class="modal fade" id="modalViewFoto" tabindex="-1" aria-labelledby="modalViewFotoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalViewFotoLabel">Foto Pengaduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="fotoPengaduan" src="" alt="Foto Pengaduan" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fotoElements = document.querySelectorAll('img[data-bs-target="#modalViewFoto"]');
        fotoElements.forEach(foto => {
            foto.addEventListener('click', function () {
                const fotoSrc = this.getAttribute('data-foto');
                console.log('Foto URL:', fotoSrc); // Debugging: Tampilkan URL di konsol
                document.getElementById('fotoPengaduan').src = fotoSrc; // Set src untuk modal
            });
        });
    });
</script>
@endsection
