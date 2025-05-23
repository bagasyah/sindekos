@extends('layouts.admin')

@section('title', 'Edit Kamar')

@section('content')
<div class="container">
    <h1>Edit Kamar - {{ $indekos->nama }}</h1>
    <form action="{{ route('kamar.update', ['indekosId' => $indekos->id, 'kamarId' => $kamar->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="mb-3">
                <label for="edit_no_kamar" class="form-label">Nomor Kamar</label>
                <input type="text" class="form-control @error('no_kamar') is-invalid @enderror" id="edit_no_kamar" name="no_kamar" value="{{ old('no_kamar', $kamar->no_kamar) }}" required>
                @error('no_kamar')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="edit_harga" class="form-label">Harga</label>
                <input type="text" class="form-control @error('harga') is-invalid @enderror" id="edit_harga" name="harga" value="{{ old('harga', $kamar->harga) }}" required>
                @error('harga')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="edit_fasilitas_id" class="form-label">Fasilitas</label>
                <select class="form-control @error('fasilitas_id') is-invalid @enderror" id="edit_fasilitas_id" name="fasilitas_id[]" multiple="multiple" required>
                    @foreach ($fasilitas as $item)
                        <option value="{{ $item->id }}" {{ in_array($item->id, old('fasilitas_id', explode(',', $kamar->fasilitas_id))) ? 'selected' : '' }}>
                            {{ $item->nama_fasilitas }}
                        </option>
                    @endforeach
                </select>
                @error('fasilitas_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="edit_status" class="form-label">Status</label>
                <select class="form-control" id="edit_status" name="status" required>
                    <option value="Terisi" {{ old('status', $kamar->status) == 'Terisi' ? 'selected' : '' }}>Terisi</option>
                    <option value="Tidak Terisi" {{ old('status', $kamar->status) == 'Tidak Terisi' ? 'selected' : '' }}>Tidak Terisi</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#edit_fasilitas_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih fasilitas',
            allowClear: true
        });
    });
</script>
@endsection
