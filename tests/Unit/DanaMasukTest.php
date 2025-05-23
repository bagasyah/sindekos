<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Indekos;
use App\Models\Kamar;
use App\Models\Payment;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;


class DanaMasukTest extends TestCase
{
    public function test_update_payment_status()
    {
        // Buat data indekos
        $indekos = Indekos::factory()->create();
        
        // Buat fasilitas dengan hanya field yang ada di tabel
        $fasilitas = Fasilitas::factory()->make([
            'nama_fasilitas' => 'Fasilitas Test',
        ])->create();

        // Buat kamar dengan indekos_id dan fasilitas_id
        $kamar = Kamar::factory()->create([
            'indekos_id' => $indekos->id,
            'fasilitas_id' => $fasilitas->id
        ]);

        // Buat user dengan kamar_id
        $user = User::factory()->create([
            'kamar_id' => $kamar->id
        ]);

        // Buat data pembayaran terkait dengan user
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'Belum Dibayar',
            'tanggal_bayar' => now(),
            'price' => 500000,
            'batas_pembayaran' => now()->addMonth()
        ]);

        // Set URL sebelumnya
        $this->from("/pemasukan/{$indekos->id}");

        // Simulasi permintaan update
        $response = $this->post(route('pemasukan.update', $payment->id), [
            'status' => 'Selesai'
        ]);

        // Assertions
        $this->assertEquals('Selesai', $payment->fresh()->status);
        $response->assertRedirect("/pemasukan/{$indekos->id}"); // Assert redirect ke URL sebelumnya
        $response->assertSessionHas('error', 'Indekos ID tidak ditemukan.');
    }

    public function test_create_payments()
    {
        // Buat data indekos
        $indekos = Indekos::factory()->create();
        
        // Buat fasilitas dengan hanya field yang ada di tabel
        $fasilitas = Fasilitas::factory()->create([
            'nama_fasilitas' => 'Fasilitas Test',
        ]);

        // Buat kamar dengan indekos_id dan fasilitas_id
        $kamar = Kamar::factory()->create([
            'indekos_id' => $indekos->id,
            'fasilitas_id' => $fasilitas->id
        ]);

        // Buat user dengan kamar_id
        $user = User::factory()->create([
            'kamar_id' => $kamar->id
        ]);

        // Simulasi menjalankan perintah create payments
        $this->artisan('payments:create');

        // Assertions untuk memastikan pembayaran telah dibuat
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'status' => 'Belum Dibayar',
        ]);
    }
}
