@extends('layouts.admin')

@section('title', 'Kelola Akun')

@section('page_title', 'Kelola Akun')

@section('content')
    <head>
        <link rel="stylesheet" href="{{ asset('css/kelolaakun.css') }}">
    </head>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Akun</h3>
                    <a href="{{ route('createakun') }}" class="btn btn-primary float-right">
                        Tambah Akun
                    </a>
                </div>
                <div class="card-body">
                    <!-- Form Pencarian -->
                    <form method="GET" action="{{ route('kelolaakun') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama, email, no kamar, atau indekos" value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-success" type="submit">Cari</button>
                                <a href="{{ route('kelolaakun') }}" class="btn btn-danger">Reset</a>
                                <a href="{{ route('kelolaakun', array_merge(request()->query(), ['show_hidden' => request('show_hidden') ? null : '1'])) }}" 
                                   class="btn {{ request('show_hidden') ? 'btn-warning' : 'btn-secondary' }}"
                                   title="{{ request('show_hidden') ? 'Sembunyikan Akun Hidden' : 'Tampilkan Akun Hidden' }}">
                                    <i class="fas {{ request('show_hidden') ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                                <th class="align-middle">No</th>
                                <th class="align-middle">
                                    <a href="{{ route('kelolaakun', ['sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                        Nama
                                        @if(request('sort') == 'name')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle">
                                    <a href="{{ route('kelolaakun', ['sort' => 'email', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                        Email
                                        @if(request('sort') == 'email')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle">
                                    <a href="{{ route('kelolaakun', ['sort' => 'no_telp', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                        No Telepon
                                        @if(request('sort') == 'no_telp')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle">
                                    <a href="{{ route('kelolaakun', ['sort' => 'role', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                        Role
                                        @if(request('sort') == 'role')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle">
                                    <a href="{{ route('kelolaakun', ['sort' => 'no_kamar', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                        No Kamar
                                        @if(request('sort') == 'no_kamar')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle">
                                    <a href="{{ route('kelolaakun', ['sort' => 'indekos', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                        Indekos
                                        @if(request('sort') == 'indekos')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle">
                                    <a href="{{ route('kelolaakun', ['sort' => 'status', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" class="text-dark">
                                        Status
                                        @if(request('sort') == 'status')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                        @else
                                            <i class="fas fa-sort"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="align-middle">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data as $index => $d)
                                <tr>
                                    <td class="align-middle">{{ $index + 1 }}</td>
                                    <td class="align-middle">{{ ucfirst($d->name) }}</td>
                                    <td class="align-middle">{{ $d->email }}</td>
                                    <td class="align-middle">{{ $d->no_telp }}</td>
                                    <td class="align-middle">{{ ucfirst($d->role) }}</td>
                                    <td class="align-middle">
                                        {{ $d->kamar ? $d->kamar->no_kamar : '-' }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $d->kamar && $d->kamar->indekos ? $d->kamar->indekos->nama : '-' }}
                                    </td>
                                    <td class="align-middle">{{ $d->status }}</td>
                                    <td class="align-middle">
                                        @if($d->role !== 'admin')
                                            <a href="{{ route('editakun', ['id' => $d->id]) }}" class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection