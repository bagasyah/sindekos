@extends('layouts.admin')

@section('title', 'Merubah Akun')

@section('page_title', 'Merubah Akun')

@section('additional_css')
<style>
    .error-message {
        color: red;
        font-size: 0.8em;
        margin-top: 5px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Merubah Akun</h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('updateakun',['id' =>$data->id]) }}" method="POST" id="formTambahAkun">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{$data->name}}" placeholder="nama" required pattern="[A-Za-z\s]+" title="Nama hanya boleh berisi huruf dan spasi">
                        <span class="error-message" id="nama-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{$data->email}}" placeholder="email" required>
                        <span class="error-message" id="email-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="no_telp">No. Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" value="{{$data->no_telp}}" placeholder="No. Telepon" required pattern="\d*">
                        <span class="error-message" id="no_telp-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role">
                            <option value="user" {{ old('role', $data->role ?? '') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $data->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="active" {{ old('status', $data->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="non active" {{ old('status', $data->status ?? '') == 'non active' ? 'selected' : '' }}>Non Active</option>
                            <option value="hidden" {{ old('status', $data->status ?? '') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="indekos_id">Indekos</label>
                        <input type="text" class="form-control" id="indekos_id" name="indekos_id" value="{{ $data->nama_indekos }}" readonly>
                        <span class="error-message" id="indekos-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="kamar_id">Nomor Kamar</label>
                        <input type="text" class="form-control" id="kamar_id" name="kamar_id" value="{{ $data->kamar ? $data->kamar->no_kamar : '-' }}" readonly>
                        <span class="error-message" id="kamar-error"></span>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('kelolaakun') }}" class="btn btn-danger">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_js')
<script>
    $(document).ready(function() {
        // Inisialisasi data kamar saat halaman dimuat
        var initialIndekosId = $('#indekos_id').val();
        if (initialIndekosId) {
            loadKamars(initialIndekosId, {{ $data->kamar_id }});
        }

        $('#indekos_id').change(function() {
            var indekosId = $(this).val();
            if (indekosId) {
                loadKamars(indekosId);
            } else {
                $('#kamar_id').empty();
                $('#kamar_id').append('<option value="">Pilih Nomor Kamar</option>');
                $('#kamar_id').prop('disabled', true);
            }
        });

        function loadKamars(indekosId, selectedKamarId = null) {
            $.ajax({
                url: '/get-kamars/' + indekosId,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('#kamar_id').empty();
                    $('#kamar_id').append('<option value="">Pilih Nomor Kamar</option>');
                    $.each(data, function(key, value) {
                        var selected = selectedKamarId == value.id ? 'selected' : '';
                        $('#kamar_id').append('<option value="'+ value.id +'" '+ selected +'>'+ value.no_kamar +'</option>');
                    });
                    $('#kamar_id').prop('disabled', false);
                }
            });
        }

        $('#formTambahAkun').submit(function(e) {
            var isValid = true;
            $('.error-message').text('');

            // Validasi nama (hanya huruf dan spasi)
            var namaRegex = /^[A-Za-z\s]+$/;
            if ($('#nama').val().trim() === '') {
                $('#nama-error').text('Nama wajib diisi');
                isValid = false;
            } else if (!namaRegex.test($('#nama').val().trim())) {
                $('#nama-error').text('Nama hanya boleh berisi huruf dan spasi');
                isValid = false;
            }

            if ($('#email').val().trim() === '') {
                $('#email-error').text('Email wajib diisi');
                isValid = false;
            }

            // Validasi nomor telepon (hanya angka)
            var telpRegex = /^\d+$/;
            if ($('#no_telp').val().trim() === '') {
                $('#no_telp-error').text('No. Telepon wajib diisi');
                isValid = false;
            } else if (!telpRegex.test($('#no_telp').val().trim())) {
                $('#no_telp-error').text('No. Telepon hanya boleh berisi angka');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                alert('Mohon isi semua field yang wajib diisi dengan benar');
            }
        });
    });
</script>
@endsection