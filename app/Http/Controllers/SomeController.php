<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class SomeController extends Controller
{
    public function store(Request $request)
    {
        // Kode untuk menyimpan pengguna
        // ...

        // Menyimpan pembayaran dengan nilai price yang valid
        Payment::create([
            'user_id' => $user->id,
            'tanggal_bayar' => now(),
            'batas_pembayaran' => now()->addMonth(),
            'status' => 'Belum Dibayar',
            'price' => 100000, // Berikan nilai yang valid
            'jenis' => 'Penyewa',
        ]);
    }
} 