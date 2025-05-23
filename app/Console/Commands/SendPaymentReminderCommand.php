<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\PaymentReminder;
use Illuminate\Support\Facades\Mail;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\PaymentReminderNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Notifications\PaymentExpiredNotification;

class SendPaymentReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send payment reminder emails to users and admin';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // --- Reminder H-1 ---
        $payments = Payment::whereDate('batas_pembayaran', now()->addDay()->toDateString())
            ->where('status', '!=', 'Selesai')
            ->get();

        foreach ($payments as $payment) {
            $user = $payment->user;

            if ($user && $user->status === 'active') {
                $data = [
                    'title' => 'Peringatan Pembayaran',
                    'message' => 'Batas pembayaran Anda akan jatuh tempo besok.',
                    'nama' => $user->name,
                    'batas_pembayaran' => Carbon::parse($payment->batas_pembayaran)->format('d-m-Y'),
                    'payment_id' => $payment->id,
                ];

                try {
                    Mail::to($user->email)->send(new PaymentReminder($data));
                } catch (\Exception $e) {
                    Log::error("Failed to send reminder email to user {$user->email}: " . $e->getMessage());
                }

                try {
                    $user->notify(new PaymentReminderNotification($data));
                } catch (\Exception $e) {
                    Log::error("Failed to send notification to user {$user->email}: " . $e->getMessage());
                }

                // Get admin users
                $adminUsers = User::where('role', 'admin')->get();

                foreach ($adminUsers as $adminUser) {
                    if ($adminUser->email) {
                        $adminData = [
                            'title' => 'Peringatan Pembayaran User (H-1)',
                            'message' => "User {$user->name} memiliki batas pembayaran besok (ID: {$payment->id}).",
                            'nama' => $user->name,
                            'email' => $user->email,
                            'batas_pembayaran' => Carbon::parse($payment->batas_pembayaran)->format('d-m-Y'),
                            'payment_id' => $payment->id,
                        ];

                        try {
                            Mail::to($adminUser->email)->send(new PaymentReminder($adminData));
                        } catch (\Exception $e) {
                            Log::error("Failed to send reminder email to admin {$adminUser->email}: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        // --- Reminder Pembayaran yang Sudah Habis ---
        $expiredPayments = Payment::whereDate('batas_pembayaran', '<', now()->toDateString())
            ->where('status', '!=', 'Selesai')
            ->get();

        foreach ($expiredPayments as $payment) {
            $user = $payment->user;

            if ($user && $user->status === 'active') {
                $data = [
                    'title' => 'Peringatan Pembayaran Habis',
                    'message' => 'Masa pembayaran Anda sudah habis.',
                    'nama' => $user->name,
                    'batas_pembayaran' => Carbon::parse($payment->batas_pembayaran)->format('d-m-Y'),
                    'payment_id' => $payment->id,
                ];

                try {
                    Mail::to($user->email)->send(new PaymentReminder($data));
                } catch (\Exception $e) {
                    Log::error("Failed to send expired payment email to user {$user->email}: " . $e->getMessage());
                }

                try {
                    $user->notify(new PaymentExpiredNotification($data));
                } catch (\Exception $e) {
                    Log::error("Failed to send notification to user {$user->email}: " . $e->getMessage());
                }

                // Get admin users
                $adminUsers = User::where('role', 'admin')->get();

                foreach ($adminUsers as $adminUser) {
                    if ($adminUser->email) {
                        $kamar = $user->kamar;
                        $indekos = $kamar ? $kamar->indekos : null;
                
                        // Format tanggal dengan Carbon
                        $batasPembayaranFormatted = Carbon::parse($payment->batas_pembayaran)->format('d F Y');
                
                        $adminData = [
                            'title' => 'Peringatan Pembayaran Pengguna (Jatuh Tempo)',
                            'message' => "Dengan hormat,\n\n" .
                                         "Kami informasikan bahwa pembayaran dari pengguna dengan detail berikut telah melewati batas waktu yang ditentukan:\n\n" .
                                         "Nama Pengguna: {$user->name}\n" .
                                         "Nomor Kamar: " . ($kamar ? $kamar->no_kamar : 'Tidak terdaftar') . "\n" .
                                         "Nama Indekos: " . ($indekos ? $indekos->nama : 'Tidak terdaftar') . "\n" .
                                         "Batas Waktu Pembayaran: {$batasPembayaranFormatted}\n\n" .
                                         "Mohon untuk segera menindaklanjuti informasi ini.\n\n" .
                                         "Terima kasih atas perhatiannya.",
                        ];
                
                        try {
                            Mail::to($adminUser->email)->send(new PaymentReminder($adminData));
                        } catch (\Exception $e) {
                            Log::error("Failed to send expired payment email to admin {$adminUser->email}: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        $this->info('Payment reminders sent successfully.');
    }
}