<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly bool $changedByAdmin) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Password Akun PENA Berubah')
            ->greeting('Halo, '.$notifiable->name)
            ->line($this->changedByAdmin
                ? 'Password akun PENA Anda telah diubah oleh administrator.'
                : 'Password akun PENA Anda telah berhasil diubah.')
            ->line('Demi keamanan, sesi aktif lain telah dikeluarkan dari sistem.');

        return $message
            ->action('Masuk ke PENA', route('login'))
            ->line('Jika Anda tidak mengenali perubahan ini, segera gunakan fitur Lupa Password dan hubungi administrator.');
    }
}