@extends('layouts.indekos-layout')

@section('page_title', 'Daftar Fasilitas - ' . $indekos->nama)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Fasilitas - {{ $indekos->nama }}</h3>
                <button type="button" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#tambahFasilitasModal">
                    Tambah Fasilitas
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Fasilitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fasilitas as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_fasilitas }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editFasilitasModal{{ $item->id }}" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapusFasilitasModal{{ $item->id }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Edit Fasilitas -->
                        <div class="modal fade" id="editFasilitasModal{{ $item->id }}" tabindex="-1" aria-labelledby="editFasilitasModalLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editFasilitasModalLabel{{ $item->id }}">Edit Fasilitas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('fasilitas.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="nama_fasilitas_edit_{{ $item->id }}" class="form-label">Nama Fasilitas</label>
                                                <input type="text" class="form-control" id="nama_fasilitas_edit_{{ $item->id }}" name="nama_fasilitas" value="{{ $item->nama_fasilitas }}" required>
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

                        <!-- Modal Hapus Fasilitas -->
                        <div class="modal fade" id="hapusFasilitasModal{{ $item->id }}" tabindex="-1" aria-labelledby="hapusFasilitasModalLabel{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="hapusFasilitasModalLabel{{ $item->id }}">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus fasilitas {{ $item->nama_fasilitas }}?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('fasilitas.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada fasilitas tersedia</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Fasilitas -->
<div class="modal fade" id="tambahFasilitasModal" tabindex="-1" aria-labelledby="tambahFasilitasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahFasilitasModalLabel">Tambah Fasilitas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTambahFasilitas" action="{{ route('fasilitas.store', $indekos->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_fasilitas" class="form-label">Nama Fasilitas</label>
                        <input type="text" class="form-control" id="nama_fasilitas" name="nama_fasilitas" required>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Daftar nama fasilitas yang sudah ada, dalam array js
    const existingFacilities = @json($fasilitas->pluck('nama_fasilitas'));
    console.log('Existing facilities:', existingFacilities);

    // Validasi form tambah fasilitas
    $('#formTambahFasilitas').submit(function(e) {
        e.preventDefault();
        var isValid = true;
        const inputVal = $('#nama_fasilitas').val().trim();
        console.log('Input value:', inputVal);
        console.log('Is input in existing facilities:', existingFacilities.includes(inputVal));

        // Validasi nama fasilitas kosong
        if (inputVal === '') {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Nama fasilitas wajib diisi',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            isValid = false;
        }
        // Validasi nama fasilitas sudah ada
        else if (existingFacilities.includes(inputVal)) {
            console.log('Showing duplicate alert');
            Swal.fire({
                title: 'Peringatan!',
                text: 'Nama fasilitas sudah ada. Silakan gunakan nama lain.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            isValid = false;
        }

        if (isValid) {
            console.log('Form is valid, submitting...');
            this.submit();
        }
    });
});
</script>
@endpush