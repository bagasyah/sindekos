@extends('layouts.user')

@section('title', 'Notifikasi Pengguna')

@section('page_title', 'Notifikasi Pengguna')

@section('content')
<div class="container">
    <h1>Notifikasi Anda</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Pesan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach (auth()->user()->unreadNotifications as $notification)
                <tr>
                    <td>{{ $notification->data['title'] ?? '-' }}</td>
                    <td>{{ $notification->data['message'] }}</td>
                    <td>{{ $notification->created_at->format('d-m-Y H:i') }}</td>
                    <td>
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-primary">Tandai Dibaca</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection