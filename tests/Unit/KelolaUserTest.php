<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Indekos;
use App\Models\Kamar;

use Illuminate\Support\Facades\Hash;
use Tests\TestCase;


class KelolaUserTest extends TestCase
{
   

    /** @test */
     public function it_can_create_a_user_account()
     {
         // Buat pengguna yang terautentikasi
         $this->actingAs(User::factory()->create(['role' => 'admin']));

         // Ambil kamar yang sudah ada di database
         $kamar = Kamar::first(); // Pastikan ada kamar yang tersedia di database

         // Buat indekos yang valid
         $indekos = Indekos::factory()->create(); // Pastikan Anda memiliki factory untuk Indekos

         // Kirim permintaan untuk membuat akun pengguna baru
         $response = $this->post(route('storeakun'), [
             'nama' => 'John Doe', // Pastikan nama parameter sesuai dengan yang diharapkan
             'email' => 'john@example.com',
             'no_telp' => '1234567890',
             'password' => 'password',
             'role' => 'user',
             'indekos_id' => $indekos->id, // Gunakan ID indekos yang valid
           'kamar_id' => $kamar->id, // Gunakan ID kamar yang valid
        ]);

        // Periksa apakah pengguna diarahkan ke halaman kelola akun
        $response->assertRedirect(route('kelolaakun'));

        // Periksa apakah pengguna baru ada di database
        $this->assertDatabaseHas('users', [
           'email' => 'john@example.com',
       ]);
     }

    /** @test */
    public function it_can_edit_a_user_account()
    {
        // Ambil indekos dan kamar yang sudah ada di database
        $indekos = Indekos::first(); // Pastikan ada indekos yang tersedia
        $kamar = Kamar::first(); // Pastikan ada kamar yang tersedia

        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'no_telp' => '0987654321',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'kamar_id' => $kamar->id,
        ]);

        // Autentikasi pengguna sebagai admin
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->put(route('updateakun', ['id' => $user->id]), [
            'nama' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'no_telp' => '0987654321',
            'status' => 'active',
            'indekos_id' => $indekos->id, // Gunakan ID indekos yang valid
           
        ]);

        $response->assertRedirect(route('kelolaakun'));
        $this->assertDatabaseHas('users', [
            'email' => 'jane.smith@example.com',
        ]);
    }
}