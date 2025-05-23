@extends('layouts.indekos-layout')
@section('title', 'Pemasukan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Inventory / Pemasukan</h3>
                <div class="card-tools">
                    <form class="form-inline" method="GET" action="{{ route('pemasukan.index', ['indekosId' => $indekos->id]) }}">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Cari nama...">
                        <input type="date" name="start_date" class="form-control mr-2" placeholder="Tanggal awal">
                        <input type="date" name="end_date" class="form-control mr-2" placeholder="Tanggal akhir">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        <button type="button" class="btn btn-danger ml-2" onclick="window.location='{{ route('pemasukan.index', ['indekosId' => $indekos->id]) }}'">
                            <i class="fas fa-sync-alt"></i>
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
                                    <a href="{{ route('pemasukan.index', [
                                        'indekosId' => $indekos->id,
                                        'sort_by' => 'name',
                                        'sort_direction' => request('sort_by') == 'name' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                        'search' => request('search'),
                                        'start_date' => request('start_date'),
                                        'end_date' => request('end_date')
                                    ]) }}" class="text-dark">
                                        NAMA 
                                        @if(request('sort_by') == 'name')
                                            <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('pemasukan.index', [
                                        'indekosId' => $indekos->id,
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
                                <th>Jenis</th>
                                <th>
                                    <a href="{{ route('pemasukan.index', [
                                        'indekosId' => $indekos->id,
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
                                    <a href="{{ route('pemasukan.index', [
                                        'indekosId' => $indekos->id,
                                        'sort_by' => 'batas_pembayaran',
                                        'sort_direction' => request('sort_by') == 'batas_pembayaran' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                        'search' => request('search'),
                                        'start_date' => request('start_date'),
                                        'end_date' => request('end_date')
                                    ]) }}" class="text-dark">
                                        Batas Pembayaran
                                        @if(request('sort_by') == 'batas_pembayaran')
                                            <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('pemasukan.index', [
                                        'indekosId' => $indekos->id,
                                        'sort_by' => 'price',
                                        'sort_direction' => request('sort_by') == 'price' && request('sort_direction') == 'asc' ? 'desc' : 'asc',
                                        'search' => request('search'),
                                        'start_date' => request('start_date'),
                                        'end_date' => request('end_date')
                                    ]) }}" class="text-dark">
                                        Jumlah Uang
                                        @if(request('sort_by') == 'price')
                                            <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('pemasukan.index', [
                                        'indekosId' => $indekos->id,
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                            <tr>
                                <td>{{ $payment->user->name }}</td>
                                <td>{{ $payment->user->kamar->no_kamar }}</td>
                                <td>Penyewa</td>
                                <td>{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->batas_pembayaran)->format('d-m-Y') }}</td>
                                <td>{{ number_format($payment->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $payment->status == 'Selesai' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $payment->status == 'Selesai' ? 'Selesai' : 'Belum Dibayar' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $payment->id }}">Edit</button>
                                </td>
                            </tr>
                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $payment->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $payment->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel{{ $payment->id }}">Edit Status Pembayaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-control" id="status" name="status" required>
                                                        <option value="Belum Dibayar" {{ $payment->status == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                                        <option value="Selesai" {{ $payment->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data pemasukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
