<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Pengaduan;
use App\Models\User;
use App\Notifications\PengaduanBaruNotification;
use Illuminate\Support\Facades\Notification;

class PengaduanNotifikasiTest extends TestCase
{
    /** @test */
    public function pengaduan_baru_notification()
    {
        Notification::fake();

        // Membuat pengguna admin
        $admin = User::factory()->create(['role' => 'admin']);
        $pengaduan = Pengaduan::factory()->create(['user_id' => $admin->id]);

        // Kirim notifikasi ke admin
        $admin->notify(new PengaduanBaruNotification($pengaduan));

        // Memastikan notifikasi telah dikirim ke admin
        Notification::assertSentTo($admin, PengaduanBaruNotification::class);
    }
}
