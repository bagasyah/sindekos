<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';
    protected $fillable = ['indekos_id', 'nama', 'jenis', 'tanggal', 'jumlah_uang','status'];

    public function indekos()
    {
        return $this->belongsTo(Indekos::class);
    }
}
