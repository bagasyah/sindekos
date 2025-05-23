@extends('layouts.admin')

@section('page_title', 'Daftar Fasilitas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Fasilitas</h3>
                <button type="button" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#tambahFasilitasModal">
                    Tambah Fasilitas
                </button>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Form Pencarian -->
                <form method="GET" action="{{ route('fasilitas.index') }}" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari fasilitas" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit">Cari</button>
                            <a href="{{ route('fasilitas.index') }}" class="btn btn-danger">Reset</a>
                        </div>
                    </div>
                </form>

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
                                    <i class="fas fa-edit"></i>
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
                                                <label for="nama_fasilitas" class="form-label">Nama Fasilitas</label>
                                                <input type="text" class="form-control @error('nama_fasilitas') is-invalid @enderror" id="nama_fasilitas" name="nama_fasilitas" value="{{ old('nama_fasilitas', $item->nama_fasilitas) }}" required pattern="[A-Za-z\s]+" title="Hanya boleh berisi huruf dan spasi">
                                                @error('nama_fasilitas')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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
            <form action="{{ route('fasilitas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label for="nama_fasilitas" class="form-label">Nama Fasilitas</label>
                        <input type="text" class="form-control @error('nama_fasilitas') is-invalid @enderror" id="nama_fasilitas" name="nama_fasilitas" value="{{ old('nama_fasilitas') }}" required pattern="[A-Za-z\s]+" title="Hanya boleh berisi huruf dan spasi">
                        @error('nama_fasilitas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
<script>
$(document).ready(function() {
    // Tampilkan modal tambah jika ada error
    @if($errors->any() && !old('_method'))
        $('#tambahFasilitasModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#tambahFasilitasModal').modal('show');
    @endif

    // Tampilkan modal edit jika ada error
    @if($errors->any() && old('_method') === 'PUT')
        $('#editFasilitasModal{{ old('id') }}').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#editFasilitasModal{{ old('id') }}').modal('show');
    @endif

    // Simpan nilai input sebelumnya jika ada error
    @if(old('nama_fasilitas'))
        $('#nama_fasilitas').val('{{ old('nama_fasilitas') }}');
    @endif
});
</script>
@endpush
