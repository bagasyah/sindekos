<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Pengaduan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class KelolaPengaduanTest extends TestCase
{
 

    public function test_user_can_create_pengaduan()
    {
        // Simulasi pengguna yang login
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->post(route('pengaduan.store'), [
            'tanggal_pelaporan' => now()->toDateString(),
            'masalah' => 'Masalah contoh',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pengaduan', [
            'user_id' => $user->id,
            'masalah' => 'Masalah contoh',
            'status' => 'Pending',
        ]);
    }

    public function test_admin_can_update_pengaduan()
    {
        // Simulasi pengguna admin yang login
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Buat pengaduan
        $pengaduan = Pengaduan::factory()->create(['status' => 'Pending']);

        $response = $this->put(route('pengaduan.update', $pengaduan->id), [
            'status' => 'Selesai',
        ]);

        $response->assertRedirect(route('admin.pengaduan'));
        $this->assertDatabaseHas('pengaduan', [
            'id' => $pengaduan->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_admin_can_view_all_pengaduan()
    {
        // Simulasi pengguna admin yang login
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Buat beberapa pengaduan
        Pengaduan::factory()->count(3)->create();

        $response = $this->get(route('admin.pengaduan'));

        $response->assertStatus(200);
        $response->assertViewHas('pengaduan');
    }

    public function test_user_can_view_own_pengaduan()
    {
        // Simulasi pengguna yang login
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        // Buat pengaduan untuk pengguna ini
        Pengaduan::factory()->create(['user_id' => $user->id]);

        $response = $this->get(route('user.pengaduan'));

        $response->assertStatus(200);
        $response->assertViewHas('pengaduan');
    }
}
