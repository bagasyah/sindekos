@extends('layouts.user')

@section('title', 'Data Penyewa')

@section('page_title', 'Data Penyewa Indekos ' . (Auth::user()->kamar && Auth::user()->kamar->indekos ? Auth::user()->kamar->indekos->nama : 'Tidak Diketahui'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table" style="border: none;">
                            <tbody>
                                <tr>
                                    <th style="border: none;">Nama</th>
                                    <td style="border: none;">: {{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <th style="border: none;">No Hp</th>
                                    <td style="border: none;">: {{ Auth::user()->no_telp }}</td>
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
                    <div class="col-md-4 text-center">
                        @php
                            $profilePhoto = Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('in.png');
                        @endphp
                        <img src="{{ $profilePhoto }}" alt="Foto Profil" class="img-fluid" style="max-width: 200px; max-height: 200px;">
                        
                        <div class="">
                            <button type="button" class="btn btn-tool" data-bs-toggle="modal" data-bs-target="#editPhotoModal">
                                <i class="fas fa-edit"></i> Edit Foto
                            </button>
                        </div>
                        <h5 class="mt-1 mb-2" style="border: 1px solid red; background-color: red; color: white; padding: 5px; border-radius: 2px; max-width: 300px; margin: 0 auto;">
                            @if($batasPembayaranTerbaru)
                                Batas Pembayaran: {{ \Carbon\Carbon::parse($batasPembayaranTerbaru->batas_pembayaran)->format('d-m-Y') }}
                            @else
                                Tidak ada pembayaran tertunda
                            @endif
                        </h5>
                        
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Bayar</th>
                                <th>Batas Pembayaran</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($payments as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->batas_pembayaran)->format('d-m-Y') }}</td>
                                <td>{{ $payment->price }}</td>
                                <td>
                                    <span class="badge {{ $payment->status == 'Selesai' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $payment->status == 'Selesai' ? 'Selesai' : 'Belum Dibayar' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="editPhotoModal" tabindex="-1" aria-labelledby="editPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPhotoModalLabel">Edit Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="foto">Pilih Foto Baru</label>
                        <input type="file" class="form-control" id="foto" name="foto" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Update Foto</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection