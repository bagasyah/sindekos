<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\Indekos;
use App\Models\Payment;
use App\Models\Pengeluaran;
use App\Models\User;
use App\Models\Kamar;
use Illuminate\Foundation\Testing\WithFaker;

class ViewKeuanganIndekos extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        // Setup data awal
        $this->indekos = Indekos::factory()->create();
        $this->user = User::factory()->create();
        
        // Pastikan kamar ada sebelum mengaitkan
        $kamar = Kamar::factory()->create(['indekos_id' => $this->indekos->id]);
        
        // Jika relasi adalah BelongsTo, set ID kamar di user
        $this->user->kamar_id = $kamar->id; // Ganti dengan kolom yang sesuai
        $this->user->save();
    }

    public function testIndexReturnsViewWithData()
    {
        // Simulasi data pembayaran
        Payment::create([
            'user_id' => $this->user->id,
            'indekos_id' => $this->indekos->id,
            'tanggal_bayar' => now(),
            'price' => 100000,
            'status' => 'Selesai',
        ]);

        // Simulasi data pengeluaran
        Pengeluaran::create([
            'indekos_id' => $this->indekos->id,
            'nama' => 'Biaya Listrik',
            'tanggal' => now(),
            'jumlah_uang' => 50000,
            'status' => 'Selesai',
            'jenis' => 'Tagihan',
        ]);

        $response = $this->get(route('riwayat_keuangan.index', ['indekos' => $this->indekos->id]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.indekos.riwayat_keuangan');
        $response->assertViewHas('riwayats');
        $response->assertViewHas('totalJumlahUang');
        $response->assertViewHas('totalPemasukan');
        $response->assertViewHas('totalPengeluaran');
    }

    // public function testIndexRedirectsWithErrorOnInvalidDate()
    // {
    //     $response = $this->get(route('riwayat_keuangan.index', ['indekos' => $this->indekos->id]) . '?start_date=2023-10-10&end_date=2023-10-01');

    //     $response->assertRedirect();
    //     $response->assertSessionHasErrors(['error' => 'Urutan tanggal yang dimasukkan salah. Tanggal akhir tidak boleh lebih awal dari tanggal mulai.']);
    // }

    // public function testExportReturnsExcelFile()
    // {
    //     $response = $this->get(route('riwayat-keuangan.export', ['indekosId' => $this->indekos->id]));

    //     $response->assertStatus(200);
    //     $this->assertEquals('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));
    // }
}
