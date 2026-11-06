<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly string $token) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Reset Password PENA')
            ->greeting('Halo, '.$notifiable->name)
            ->line('Kami menerima permintaan pengaturan ulang password akun PENA Anda.')
            ->action('Atur Ulang Password', $url)
            ->line('Tautan ini berlaku selama 60 menit dan hanya dapat digunakan sekali.')
            ->line('Abaikan email ini jika Anda tidak meminta reset password.');
    }
}