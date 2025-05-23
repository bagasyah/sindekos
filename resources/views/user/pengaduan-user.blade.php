@extends('layouts.user') <!-- Pastikan Anda menggunakan layout yang sesuai -->
@section('title', 'Pengaduan')

@section('page_title', 'Pengaduan')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Pengaduan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPengaduan">
                        Tambah Pengaduan
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No Kamar</th>
                            <th>Tanggal Pelaporan</th>
                            <th>Tanggal Perbaikan</th>
                            <th>Masalah</th>
                            <th>Status</th>
                            <th>Foto</th>
                            <th>Foto Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengaduan as $item)
                        <tr>
                            <td>{{ $item->user->kamar->no_kamar ?? '-' }}</td>
                            <td>{{ $item->tanggal_pelaporan }}</td>
                            <td>{{ $item->tanggal_perbaikan }}</td>
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
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto_akhir) }}" alt="Foto" style="width: 150px; height: auto;" class="img-thumbnail" data-bs-toggle="modal" data-bs-target="#modalViewFoto" data-foto="{{ asset('storage/' . $item->foto_akhir) }}">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($item->status != 'Selesai')
                                <form action="{{ route('pengaduan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengaduan -->
<div class="modal fade" id="modalTambahPengaduan" tabindex="-1" aria-labelledby="modalTambahPengaduanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahPengaduanLabel">Tambah Pengaduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" id="formPengaduan">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Foto wajib diisi sebagai bukti pengaduan
                    </div>
                    <input type="hidden" name="no_kamar" value="{{ Auth::user()->kamar->no_kamar ?? '' }}">
                    
                    <div class="mb-3">
                        <label for="masalah" class="form-label">Masalah</label>
                        <textarea class="form-control" id="masalah" name="masalah" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" capture="camera" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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
    // Script untuk menampilkan foto di modal
    document.addEventListener('DOMContentLoaded', function () {
        const fotoElements = document.querySelectorAll('img[data-bs-target="#modalViewFoto"]');
        fotoElements.forEach(foto => {
            foto.addEventListener('click', function () {
                const fotoSrc = this.getAttribute('data-foto');
                console.log('Foto URL:', fotoSrc); // Debugging: Tampilkan URL di konsol
                document.getElementById('fotoPengaduan').src = fotoSrc; // Set src untuk modal
            });
        });

        // Validasi form pengaduan
        const formPengaduan = document.getElementById('formPengaduan');
        formPengaduan.addEventListener('submit', function(e) {
            const fotoInput = document.getElementById('foto');
            if (!fotoInput.files || fotoInput.files.length === 0) {
                e.preventDefault();
                alert('Mohon lengkapi foto pengaduan terlebih dahulu!');
                fotoInput.focus();
            }
        });
    });
</script>

@endsection




