@extends('layouts.indekos-layout')

@section('title', 'Detail Indekos')

@section('page_title', 'Detail Indekos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Indekos</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Nama Indekos</th>
                        <td>{{ $indekos->nama }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Kamar</th>
                        <td>{{ $jumlahKamar }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Penghuni</th>
                        <td>{{ $jumlahPenghuni }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $kamar->alamat ?? $indekos->alamat }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Kamar</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No. Kamar</th>
                            <th>Status</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($indekos->kamars as $kamar)
                        <tr>
                            <td>{{ $kamar->no_kamar }}</td>
                            <td>
                                <span class="badge {{ $kamar->status == 'Terisi' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $kamar->status }}
                                </span>
                            </td>
                            <td>{{ $kamar->harga }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Data kamar belum tersedia</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
