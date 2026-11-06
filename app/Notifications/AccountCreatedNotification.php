<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreatedNotification extends Notification
{
    use Queueable;

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Akun PENA Dibuat')
            ->greeting('Halo, '.$notifiable->name)
            ->line('Akun PENA Anda telah dibuat dengan peran: '.ucfirst($notifiable->role).'.')
            ->line('Gunakan password sementara yang disampaikan administrator melalui kanal terpisah. Jangan membagikan password melalui email atau pesan publik.')
            ->action('Masuk ke PENA', route('login'))
            ->line('Gunakan fitur Lupa Password jika Anda belum menerima atau tidak mengetahui password sementara.');
    }
}