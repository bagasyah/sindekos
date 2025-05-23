<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Indekos;
use App\Models\Payment;
use App\Models\Pengeluaran;
use App\Models\User;
use App\Models\Kamar;

class RiwayatKeuanganTest extends TestCase
{
    protected $indekos;
    protected $user;
    protected $kamar;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Bersihkan data spesifik yang akan digunakan dalam test
        Payment::where('status', 'Selesai')->delete();
        Pengeluaran::where('status', 'Selesai')->delete();
        
        // Buat data indekos baru untuk testing
        $this->indekos = Indekos::factory()->create();
        
        // Buat data kamar baru
        $this->kamar = Kamar::factory()->create([
            'indekos_id' => $this->indekos->id
        ]);
        
        // Buat user baru
        $this->user = User::factory()->create();
        $this->user->kamar()->associate($this->kamar);
        $this->user->save();

        // Login sebagai admin
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);
        $this->actingAs($admin);
    }

    // public function test_menghitung_total_keuangan()
    // {
    //     // Hapus hanya data yang terkait dengan test ini
    //     Payment::where('user_id', $this->user->id)->delete();
    //     Pengeluaran::where('indekos_id', $this->indekos->id)->delete();

    //     // Buat beberapa data pembayaran
    //     Payment::factory()->create([
    //         'user_id' => $this->user->id,
    //         'price' => 100000,
    //         'status' => 'Selesai',
    //         'tanggal_bayar' => now(),
    //     ]);

    //     Payment::factory()->create([
    //         'user_id' => $this->user->id,
    //         'price' => 150000,
    //         'status' => 'Selesai',
    //         'tanggal_bayar' => now(),
    //     ]);

    //     // Buat beberapa data pengeluaran
    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 50000,
    //         'status' => 'Selesai',
    //         'tanggal' => now(),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 30000,
    //         'status' => 'Selesai',
    //         'tanggal' => now(),
    //     ]);

    //     // Kirim request ke endpoint riwayat keuangan
    //     $response = $this->get(route('riwayat_keuangan.index', ['indekos' => $this->indekos->id]));

    //     // Pastikan response sukses
    //     $response->assertStatus(200);

    //     // Verifikasi view memiliki data yang benar
    //     $response->assertViewHas('totalPemasukan', 250000);
    //     $response->assertViewHas('totalPengeluaran', 80000);
    //     $response->assertViewHas('totalJumlahUang', 170000);
    // }
    public function test_menghitung_total_pemasukan()
    {
        // Hapus data yang terkait
        Payment::where('user_id', $this->user->id)->delete();

        // Buat 14 data pembayaran
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 300000,
            'status' => 'Selesai',
            'tanggal_bayar' => now(),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 200000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(1),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 250000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(2),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 350000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(3),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 275000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(4),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 225000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(5),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 400000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(6),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 450000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(7),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 325000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(8),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 375000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(9),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 425000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(10),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 475000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(11),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 500000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(12),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 550000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(13),
        ]);

        // Tambahkan 14 data pembayaran tambahan
        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 600000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(14),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 650000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(15),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 700000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(16),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 750000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(17),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 800000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(18),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 850000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(19),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 900000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(20),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 950000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(21),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 1000000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(22),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 1050000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(23),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 1100000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(24),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 1150000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(25),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 1200000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(26),
        ]);

        Payment::factory()->create([
            'user_id' => $this->user->id,
            'price' => 1250000,
            'status' => 'Selesai',
            'tanggal_bayar' => now()->subDays(27),
        ]);

        // Kirim request
        $response = $this->get(route('riwayat_keuangan.index', ['indekos' => $this->indekos->id]));

        // Verifikasi total pemasukan
        $response->assertStatus(200);
        $response->assertViewHas('totalPemasukan', 18050000); // Mengubah nilai yang diharapkan sesuai total sebenarnya
    }

    // public function test_menghitung_total_pengeluaran()
    // {
    //     // Hapus data yang terkait
    //     Pengeluaran::where('indekos_id', $this->indekos->id)->delete();

    //     // Buat 14 data pengeluaran
    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 75000,
    //         'status' => 'Selesai',
    //         'tanggal' => now(),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 125000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(1),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 100000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(2),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 150000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(3),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 80000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(4),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 95000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(5),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 175000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(6),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 200000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(7),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 225000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(8),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 250000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(9),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 275000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(10),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 300000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(11),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 325000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(12),
    //     ]);

    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 350000,
    //         'status' => 'Selesai',
    //         'tanggal' => now()->subDays(13),
    //     ]);

    //     // Kirim request
    //     $response = $this->get(route('riwayat_keuangan.index', ['indekos' => $this->indekos->id]));

    //     // Verifikasi total pengeluaran
    //     $response->assertStatus(200);
    //     $response->assertViewHas('totalPengeluaran', 2725000); // Total dari 14 pengeluaran
    // }

    // public function test_menghitung_total_jumlah_uang()
    // {
    //     // Hapus data yang terkait
    //     Payment::where('user_id', $this->user->id)->delete();
    //     Pengeluaran::where('indekos_id', $this->indekos->id)->delete();

    //     // Buat data pembayaran
    //     Payment::factory()->create([
    //         'user_id' => $this->user->id,
    //         'price' => 400000,
    //         'status' => 'Selesai',
    //         'tanggal_bayar' => now(),
    //     ]);

    //     // Buat data pengeluaran
    //     Pengeluaran::factory()->create([
    //         'indekos_id' => $this->indekos->id,
    //         'jumlah_uang' => 150000,
    //         'status' => 'Selesai',
    //         'tanggal' => now(),
    //     ]);

    //     // Kirim request
    //     $response = $this->get(route('riwayat_keuangan.index', ['indekos' => $this->indekos->id]));

    //     // Verifikasi total jumlah uang (pemasukan - pengeluaran)
    //     $response->assertStatus(200);
    //     $response->assertViewHas('totalJumlahUang', 250000);
    // }
}