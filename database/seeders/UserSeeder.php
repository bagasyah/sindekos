<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Membuat pengguna admin
        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345'), // Ganti dengan password yang aman
            'role' => 'admin', // Pastikan kolom ini ada di tabel users
            'kamar_id' => '22',
            'nama_indekos' => 'Indekos Admin', // Tambahkan nilai untuk nama_indekos
        ]);
    }
}