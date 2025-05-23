@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('page_title', 'Notifikasi')

@section('content')
<div class="container">
    <h1>Notifikasi</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No Kamar</th>
                <th>Masalah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach (auth()->user()->unreadNotifications as $notification)
                <tr>
                    <td>{{ $notification->data['no_kamar'] ?? '-' }}</td>
                    <td>{{ $notification->data['masalah'] }}</td>
                    <td>
                        <form action="{{ route('adminnotifications.read', $notification->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-primary">Kerjakan</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
