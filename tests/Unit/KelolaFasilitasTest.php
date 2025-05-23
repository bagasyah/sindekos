<?php

namespace Tests\Unit;

use App\Models\Fasilitas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelolaFasilitasTest extends TestCase
{
    

    public function setUp(): void
    {
        parent::setUp();

        // Buat pengguna admin untuk pengujian dengan email unik
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin' . uniqid() . '@example.com', // Email unik
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Login sebagai admin
        $this->actingAs($admin);
    }

    public function test_store_fasilitas()
    {
        $response = $this->post(route('fasilitas.store'), [
            'nama_fasilitas' => 'Fasilitas Baru',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('fasilitas.index'));
        $response->assertSessionHas('success', 'Fasilitas berhasil ditambahkan.');

        $this->assertDatabaseHas('fasilitas', [
            'nama_fasilitas' => 'Fasilitas Baru',
        ]);
    }

    public function test_view_fasilitas()
    {
        $fasilitas = Fasilitas::create([
            'nama_fasilitas' => 'Fasilitas Uji',
        ]);

        $response = $this->get(route('fasilitas.index'));

        $response->assertStatus(200);
        $response->assertSee('Fasilitas Uji');
    }

    public function test_update_fasilitas()
    {
        $fasilitas = Fasilitas::create([
            'nama_fasilitas' => 'Fasilitas Lama',
        ]);

        $response = $this->put(route('fasilitas.update', $fasilitas->id), [
            'nama_fasilitas' => 'Fasilitas Diperbarui',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('fasilitas.index'));
        $response->assertSessionHas('success', 'Fasilitas berhasil diperbarui.');

        $this->assertDatabaseHas('fasilitas', [
            'nama_fasilitas' => 'Fasilitas Diperbarui',
        ]);
    }

    public function test_delete_fasilitas()
    {
        $fasilitas = Fasilitas::create([
            'nama_fasilitas' => 'Fasilitas Untuk Dihapus',
        ]);

        $response = $this->delete(route('fasilitas.destroy', $fasilitas->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('fasilitas.index'));
        $response->assertSessionHas('success', 'Fasilitas berhasil dihapus.');

        $this->assertDatabaseMissing('fasilitas', [
            'id' => $fasilitas->id,
        ]);
    }
}