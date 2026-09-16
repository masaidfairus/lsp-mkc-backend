<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $notifiable->loadMissing('asesiPassword');
        $asesiPassword = $notifiable->asesiPassword->asesi_password ?? 'Password belum diatur';

        return (new MailMessage)
            ->subject('Aktivasi Akun LSP MKC')
            // ->line('Terima kasih telah mendaftar. Silakan klik tombol di bawah untuk memverifikasi email Anda.')
            ->action('Verifikasi Email', $this->url)
            // ->line('Link ini hanya berlaku selama 10 menit.');
            ->markdown('vendor.notifications.email', [
                'url' => $this->url,
                'user' => $notifiable,
                'password' => $asesiPassword,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
