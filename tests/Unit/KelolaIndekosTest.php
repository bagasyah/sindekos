<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Indekos;
use App\Models\User;
use App\Models\Kamar;

class KelolaIndekosTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Buat pengguna admin untuk pengujian
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Login sebagai admin
        $this->actingAs($admin);
    }

    /** @test */
    // public function it_can_store_new_indekos()
    // {
    //     // Ambil data dari database yang sudah ada
    //     $indekos = Indekos::first(); // Mengambil indekos pertama dari database

    //     $data = [
    //         'nama' => $indekos->nama, // Menggunakan nama dari indekos yang diambil
    //         'alamat' => $indekos->alamat, // Menggunakan alamat dari indekos yang diambil
    //     ];

    //     $response = $this->post(route('indekos.store'), $data);

    //     $this->assertDatabaseHas('indekos', $data);
    //     $response->assertRedirect(route('indekos.index'));
    //     $response->assertSessionHas('success', 'Indekos berhasil ditambahkan');
    // }

    /** @test */
    public function it_can_update_existing_indekos()
    {
        $indekos = Indekos::create([
            'nama' => 'Indekos A',
            'alamat' => 'Alamat A',
        ]);

        $data = [
            'nama' => 'Indekos B',
            'alamat' => 'Alamat B',
        ];

        $response = $this->put(route('indekos.update', $indekos->id), $data);

        $this->assertDatabaseHas('indekos', $data);
        $response->assertRedirect(route('indekos.index'));
        $response->assertSessionHas('success', 'Indekos berhasil diperbarui');
    }

}

