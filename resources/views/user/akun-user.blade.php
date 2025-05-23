@extends('layouts.user')

@section('title', 'Akun')

@section('page_title', 'Akun')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Penyewa Indekos {{ Auth::user()->nama_indekos }}</h3>
                <div class="card-tools">
                    <a href="{#}" class="btn btn-tool">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <table class="table" style="border: none;"> <!-- Menghilangkan border tabel -->
                            <tbody>
                                <tr>
                                    <th style="border: none;">Nama</th>
                                    <td style="border: none;">: {{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <th style="border: none;">No Hp</th>
                                    <td style="border: none;">: {{ Auth::user()->no_hp }}</td>
                                </tr>
                                <tr>
                                    <th style="border: none;">Email</th>
                                    <td style="border: none;">: {{ Auth::user()->email }}</td>
                                </tr>
                                <tr>
                                    <th style="border: none;">No Kamar</th>
                                    <td style="border: none;">: {{ Auth::user()->kamar->no_kamar }}</td>
                                </tr>
                                <tr>
                                    <th style="border: none;">Tanggal Masuk</th>
                                    <td style="border: none;">: {{ Auth::user()->created_at->format('d-m-Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-7 text-center">
                        @php
                            // Ganti dengan URL gambar default dari folder public
                            $profilePhoto = Auth::user()->profile_photo_url ?? asset('foto.png'); // URL foto default
                        @endphp
                        <img src="{{ $profilePhoto }}" alt="Foto Profil" class="img-fluid" style="max-width: 200px; max-height: 200px;"> <!-- Ukuran diperbesar -->
                        <h5 class="mt-2">{{ Auth::user()->name }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
