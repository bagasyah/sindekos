@extends('layouts.indekos-layout')

@section('page_title', 'Data Penyewa - ' . $indekos->nama)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Penyewa Indekos - {{ $indekos->nama }}</h3>
                <div class="card-tools">
                    <form class="form-inline" method="GET" action="{{ route('penyewa.index', ['indekosId' => $indekos->id]) }}">
                        <input type="text" name="search" class="form-control mr-2" placeholder="Cari nama penyewa..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        <button type="button" class="btn btn-danger ml-2" onclick="window.location='{{ route('penyewa.index', ['indekosId' => $indekos->id]) }}'">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ route('penyewa.index', ['indekosId' => $indekos->id, 'sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                    NAMA
                                    @if(request('sort') == 'name')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('penyewa.index', ['indekosId' => $indekos->id, 'sort' => 'no_kamar', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                    No Kamar
                                    @if(request('sort') == 'no_kamar')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('penyewa.index', ['indekosId' => $indekos->id, 'sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                    Tanggal Masuk
                                    @if(request('sort') == 'created_at')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('penyewa.index', ['indekosId' => $indekos->id, 'sort' => 'batas_pembayaran', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                    Batas Pembayaran
                                    @if(request('sort') == 'batas_pembayaran')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('penyewa.index', ['indekosId' => $indekos->id, 'sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                    Status
                                    @if(request('sort') == 'status')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penyewa as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->kamar->no_kamar ?? '-' }}</td>
                            <td>{{ $item->created_at->format('d-m-Y') }}</td>
                            <td>
                                @if($item->payments->isNotEmpty())
                                    {{ \Carbon\Carbon::parse($item->payments->sortByDesc('batas_pembayaran')->first()->batas_pembayaran)->format('d-m-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $item->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada penyewa terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection