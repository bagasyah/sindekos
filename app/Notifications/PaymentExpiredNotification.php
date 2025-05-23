<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon; // Tambahkan ini

class PaymentExpiredNotification extends Notification
{
    use Queueable;

    protected $data; // Tambahkan ini

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data) // Tambahkan $data
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Kirim melalui email dan simpan ke database (opsional)
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
     public function toMail($notifiable)
    {
        $batasPembayaranFormatted = Carbon::parse($this->data['batas_pembayaran'])->format('d F Y');

        return (new MailMessage)
                    ->subject($this->data['title'])
                    ->greeting('Halo Admin,')
                    ->line(new \Illuminate\Support\HtmlString('<div style="text-align: center;">')) // Opsional: untuk centering
                    ->line("Pembayaran dari pengguna berikut telah jatuh tempo:")
                    ->line("Nama Pengguna: " . $this->data['nama'])
                    ->line("Email Pengguna: " . $this->data['email'])
                    ->line("Nomor Kamar: " . $this->data['no_kamar'])
                    ->line("Nama Indekos: " . $this->data['nama_indekos'])
                    ->line("ID Pembayaran: " . $this->data['payment_id'])
                    ->line("Batas Waktu Pembayaran: " . $batasPembayaranFormatted)
                    ->line("Mohon untuk segera menindaklanjuti.")
                    ->line(new \Illuminate\Support\HtmlString('</div>')); // Penutup div centering (opsional)
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'payment_id' => $this->data['payment_id'],
            'user_name' => $this->data['nama'],
            'message' => 'Pembayaran telah jatuh tempo.', // Pesan singkat untuk notifikasi di aplikasi (jika ada)
             // Tambahkan data lain yang mungkin berguna di aplikasi
        ];
    }
}