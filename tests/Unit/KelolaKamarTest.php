<?php

namespace Tests\Unit;

use App\Models\Kamar;
use App\Models\Indekos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelolaKamarTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Buat pengguna admin untuk pengujian
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Login sebagai admin
        $this->actingAs($admin);

        // Buat data Indekos untuk pengujian
        $this->indekos = Indekos::create(['nama' => 'Indekos Contoh']);
        
        // Buat data Kamar untuk pengujian
        $this->kamar = Kamar::create([
            'no_kamar' => '101',
            'status' => 'Tidak Terisi',
            'harga' => '1000000',
            'indekos_id' => $this->indekos->id,
        ]);
    }

    public function test_store_kamar()
    {
        $response = $this->post(route('kamar.store', $this->indekos->id), [
            'no_kamar' => '102',
            'harga' => '1500000',
            'fasilitas_id' => [1, 2], // Misalkan ada fasilitas dengan ID 1 dan 2
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('kamar.index', $this->indekos->id));
        $response->assertSessionHas('success', 'Kamar berhasil ditambahkan');

        $this->assertDatabaseHas('kamars', [
            'no_kamar' => '102',
            'harga' => '1500000',
        ]);
    }

    public function test_update_kamar()
    {
        $response = $this->put(route('kamar.update', ['indekosId' => $this->indekos->id, 'kamarId' => $this->kamar->id]), [
            'no_kamar' => '103',
            'harga' => '1200000',
            'fasilitas_id' => [1, 2],
            'status' => 'Terisi',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('kamar.index', $this->indekos->id));
        $response->assertSessionHas('success', 'Data kamar berhasil diperbarui.');

        $this->assertDatabaseHas('kamars', [
            'no_kamar' => '103',
            'harga' => '1200000',
            'status' => 'Terisi',
        ]);
    }

}
