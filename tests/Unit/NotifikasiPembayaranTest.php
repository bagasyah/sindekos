<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payment;
use App\Notifications\NewPaymentNotification;
use App\Notifications\PaymentReminderNotification;
use App\Notifications\PaymentExpiredNotification;
use Illuminate\Support\Facades\Notification;

class NotifikasiPembayaranTest extends TestCase
{
    public function test_notifikasi_pembayaran()
    {
        // Siapkan pengguna dan pembayaran
        $user = User::factory()->create(['role' => 'user', 'status' => 'active']);
        
        // Buat kamar untuk user
        $kamar = \App\Models\Kamar::factory()->create(['harga' => 1000000]);
        $user->kamar()->associate($kamar);
        $user->save();

        // Menggunakan facade untuk memantau notifikasi
        Notification::fake();

        // Jalankan perintah untuk membuat pembayaran
        $this->artisan('payments:create');

        // Pastikan notifikasi dikirim
        Notification::assertSentTo(
            [$user],
            NewPaymentNotification::class,
            function ($notification, $channels) {
                return in_array('mail', $channels);
            }
        );
    }

    public function test_notifikasi_pengingat_pembayaran()
    {
        // Siapkan pengguna dan pembayaran
        $user = User::factory()->create(['role' => 'user', 'status' => 'active']);
        
        // Buat kamar untuk user
        $kamar = \App\Models\Kamar::factory()->create(['harga' => 1000000]);
        $user->kamar()->associate($kamar);
        $user->save();

        // Buat pembayaran dengan batas waktu besok
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'Belum Dibayar',
            'batas_pembayaran' => now()->addDay()->toDateString(),
            'tanggal_bayar' => now()
        ]);

        // Menggunakan facade untuk memantau notifikasi
        Notification::fake();

        // Jalankan perintah untuk mengirim pengingat
        $this->artisan('payment:reminder');

        // Pastikan notifikasi pengingat dikirim
        Notification::assertSentTo(
            [$user],
            PaymentReminderNotification::class,
            function ($notification, $channels) {
                return in_array('mail', $channels);
            }
        );
    }

    public function test_notifikasi_pembayaran_jatuh_tempo()
    {
        // Siapkan pengguna dan pembayaran
        $user = User::factory()->create(['role' => 'user', 'status' => 'active']);
        
        // Buat kamar untuk user
        $kamar = \App\Models\Kamar::factory()->create(['harga' => 1000000]);
        $user->kamar()->associate($kamar);
        $user->save();

        // Buat pembayaran yang sudah lewat jatuh tempo
        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'status' => 'Belum Dibayar',
            'batas_pembayaran' => now()->subDay(),
            'tanggal_bayar' => now()->subDays(2)
        ]);

        // Menggunakan facade untuk memantau notifikasi
        Notification::fake();

        // Jalankan perintah untuk mengirim notifikasi jatuh tempo
        $this->artisan('payment:reminder');

        // Pastikan notifikasi jatuh tempo dikirim
        Notification::assertSentTo(
            [$user],
            PaymentExpiredNotification::class,
            function ($notification, $channels) {
                return in_array('mail', $channels);
            }
        );
    }
}
