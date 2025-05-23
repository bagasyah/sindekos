<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments'; // Nama tabel di database
    // Tambahkan kolom yang dapat diisi jika diperlukan
    protected $fillable = [
        'tanggal_bayar',
        'batas_pembayaran',
        'status',
        'price',
        'user_id',
        'jenis',
        'snap_token',
        'order_id'
    ];

    protected $dates = ['tanggal_bayar', 'batas_pembayaran']; // Menambahkan kolom tanggal
    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'batas_pembayaran' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getErrors()
    {
        return $this->errors ?: [];
    }
}
