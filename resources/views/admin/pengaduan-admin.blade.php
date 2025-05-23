@extends('layouts.admin') <!-- Pastikan Anda menggunakan layout admin yang sesuai -->
@section('title', 'Laporan')

@section('page_title', 'Laporan')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laporan Pengaduan Kamar</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Indekos</th>
                            <th>No Kamar</th>
                            <th>Tanggal Pelaporan</th>
                            <th>Masalah</th>
                            <th>Status</th>
                            <th>Foto Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengaduan as $item)
                            @if ($item->status != 'Selesai') <!-- Hanya tampilkan yang statusnya bukan Selesai -->
                            <tr>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>{{ $item->user->kamar->indekos->nama ?? '-' }}</td>
                                <td>{{ $item->user->kamar->no_kamar ?? '-' }}</td>
                                <td>{{ $item->tanggal_pelaporan }}</td>
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
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditPengaduan{{ $item->id }}" data-id="{{ $item->id }}" data-tanggal="{{ $item->tanggal_pelaporan }}" data-masalah="{{ $item->masalah }}" data-status="{{ $item->status }}" data-foto="{{ asset('storage/' . $item->foto) }}">
                                        Edit
                                    </button>
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

<!-- Modal Edit Pengaduan -->
@foreach ($pengaduan as $item)
    @if ($item->status != 'Selesai')
    <div class="modal fade" id="modalEditPengaduan{{ $item->id }}" tabindex="-1" aria-labelledby="modalEditPengaduanLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditPengaduanLabel{{ $item->id }}">Edit Pengaduan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pengaduan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editFotoAkhir{{ $item->id }}" class="form-label">Foto Akhir</label>
                            <input type="file" class="form-control" id="editFotoAkhir{{ $item->id }}" name="foto_akhir" required accept="image/*" capture="camera">
                            @if ($item->foto_akhir)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $item->foto_akhir) }}" alt="Foto Akhir Saat Ini" style="width: 150px; height: auto;" class="img-thumbnail">
                                </div>
                            @else
                                <p class="text-muted">Tidak ada foto akhir yang diunggah.</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

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
    });
</script>
@endsection

@section('scripts')
<script>
    // Script untuk mengisi modal edit dengan data yang sesuai
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('button[data-bs-target^="#modalEditPengaduan"]');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const tanggal = this.getAttribute('data-tanggal');
                const masalah = this.getAttribute('data-masalah');
                const status = this.getAttribute('data-status');

                // Set data ke dalam modal
                document.getElementById('editTanggalPelaporan' + id).value = tanggal;
                document.getElementById('editMasalah' + id).value = masalah;
                document.getElementById('editStatus' + id).value = status;
            });
        });
    });

</script>
@endsection
