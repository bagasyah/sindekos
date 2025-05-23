@extends('layouts.admin')

@section('title', 'Tambah Akun')

@section('page_title', 'Tambah Akun')

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
                <h3 class="card-title">Form Tambah Akun</h3>
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
                <form action="{{ route('storeakun') }}" method="POST" id="formTambahAkun">
                    @csrf
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukan Nama...." required pattern="[A-Za-z\s]+" title="Nama hanya boleh berisi huruf dan spasi">
                        <span class="error-message" id="nama-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukan Email...." required>
                        <span class="error-message" id="email-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="no_telp">No. Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="Masukan No. Telepon...." required pattern="\d*">
                        <span class="error-message" id="no_telp-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukan Password...." required>
                        <span class="error-message" id="password-error"></span>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="form-group" id="indekos-group">
                        <label for="indekos_id">Indekos</label>
                        <select class="form-control" id="indekos_id" name="indekos_id">
                            <option value="">Pilih Indekos</option>
                            @foreach($indekos as $indekos)
                                <option value="{{ $indekos->id }}">{{ $indekos->nama }}</option>
                            @endforeach
                        </select>
                        <span class="error-message" id="indekos-error"></span>
                    </div>
                    <div class="form-group" id="kamar-group">
                        <label for="kamar_id">Nomor Kamar</label>
                        <select class="form-control" id="kamar_id" name="kamar_id" disabled>
                            <option value="">Pilih Nomor Kamar</option>
                            @foreach($availableKamars as $kamar)
                                <option value="{{ $kamar->id }}">{{ $kamar->no_kamar }}</option>
                            @endforeach
                        </select>
                        <span class="error-message" id="kamar-error"></span>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function toggleIndekosKamarFields() {
            var role = $('#role').val();
            if (role === 'admin') {
                $('#indekos-group').hide();
                $('#kamar-group').hide();
                $('#indekos_id').prop('required', false);
                $('#kamar_id').prop('required', false);
            } else {
                $('#indekos-group').show();
                $('#kamar-group').show();
                $('#indekos_id').prop('required', true);
                $('#kamar_id').prop('required', true);
            }
        }

        $('#role').change(function() {
            toggleIndekosKamarFields();
        });

        toggleIndekosKamarFields(); // Inisialisasi saat halaman dimuat

        $('#indekos_id').change(function() {
            var indekosId = $(this).val();
            if (indekosId) {
                $.ajax({
                    url: '/get-kamars/' + indekosId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        $('#kamar_id').empty();
                        $('#kamar_id').append('<option value="">Pilih Nomor Kamar</option>');
                        $.each(data, function(key, value) {
                            $('#kamar_id').append('<option value="'+ value.id +'">'+ value.no_kamar +'</option>');
                        });
                        $('#kamar_id').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        alert('Gagal memuat data kamar. Silakan coba lagi.');
                    }
                });
            } else {
                $('#kamar_id').empty();
                $('#kamar_id').append('<option value="">Pilih Nomor Kamar</option>');
                $('#kamar_id').prop('disabled', true);
            }
        });

        $('#formTambahAkun').submit(function(e) {
            var isValid = true;
            $('.error-message').text('');

            if ($('#nama').val().trim() === '') {
                $('#nama-error').text('Nama wajib diisi');
                isValid = false;
            }

            // Validasi format email
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if ($('#email').val().trim() === '') {
                $('#email-error').text('Email wajib diisi');
                isValid = false;
            } else if (!emailRegex.test($('#email').val().trim())) {
                $('#email-error').text('Mohon masukkan format email yang benar (contoh: nama@domain.com)');
                isValid = false;
            }

            if ($('#no_telp').val().trim() === '') {
                $('#no_telp-error').text('No. Telepon wajib diisi');
                isValid = false;
            }

            if ($('#password').val().trim() === '') {
                $('#password-error').text('Password wajib diisi');
                isValid = false;
            }

            if ($('#role').val() === 'user') {
                if ($('#indekos_id').val() === '') {
                    $('#indekos-error').text('Indekos wajib dipilih');
                    isValid = false;
                }

                if ($('#kamar_id').val() === '') {
                    $('#kamar-error').text('Nomor Kamar wajib dipilih');
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert('Mohon isi semua field yang wajib diisi');
            }
        });
    });
</script>
@endsection
