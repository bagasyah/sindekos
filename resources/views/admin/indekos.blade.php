@extends('layouts.admin')

@section('title', 'Kelola Indekos')

@section('page_title', 'Kelola Indekos')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Indekos</h3>
                    <button type="button" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#tambahIndekosModal">
                        Tambah Indekos
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped text-center">
                        <thead>
                            <tr>
                                <th class="align-middle">No</th>
                                <th class="align-middle">Nama</th>
                                <th class="align-middle">Alamat</th>
                                <th class="align-middle">Jumlah Kamar</th>
                                <th class="align-middle">Jumlah Penghuni</th>
                                <th class="align-middle">Kamar Kosong</th>
                                <th class="align-middle">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($indekos as $index => $kos)
                                <tr>
                                    <td class="align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle">{{ $kos->nama }}</td>
                                    <td class="align-middle">{{ $kos->alamat }}</td>
                                    <td class="align-middle">{{ $kos->jumlah_kamar }}</td>
                                    <td class="align-middle">{{ $kos->users->where('status', 'active')->count() }}</td>
                                    <td class="align-middle">{{ $kos->jumlah_kamar - $kos->users->where('status', 'active')->count() }}</td>
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-sm btn-primary edit-btn" data-id="{{ $kos->id }}" data-nama="{{ $kos->nama }}" data-alamat="{{ $kos->alamat }}" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <a href="{{ route('indekos.detail', $kos->id) }}" class="btn btn-sm btn-success" title="Menuju">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Indekos -->
    <div class="modal fade" id="tambahIndekosModal" tabindex="-1" aria-labelledby="tambahIndekosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahIndekosModalLabel">Tambah Indekos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('indekos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Indekos</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" required>
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

    <!-- Modal Edit Indekos -->
    <div class="modal fade" id="editIndekosModal" tabindex="-1" aria-labelledby="editIndekosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editIndekosModalLabel">Edit Indekos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editIndekosForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama Indekos</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="edit_alamat" name="alamat" required>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-btn');
            const editForm = document.getElementById('editIndekosForm');
            const editNamaInput = document.getElementById('edit_nama');
            const editAlamatInput = document.getElementById('edit_alamat');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const alamat = this.getAttribute('data-alamat');

                    editNamaInput.value = nama;
                    editAlamatInput.value = alamat;
                    editForm.action = `/indekos/${id}`;

                    const editModal = new bootstrap.Modal(document.getElementById('editIndekosModal'));
                    editModal.show();
                });
            });
        });
    </script>
@endsection