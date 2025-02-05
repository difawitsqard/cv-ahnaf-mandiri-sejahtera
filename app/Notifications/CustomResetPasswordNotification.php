<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable)
    {
        $url = $this->resetUrl($notifiable);

        return (new MailMessage())
            ->from(config('mail.from.address'), getCompanyInfo()->name ?? config('mail.from.name'))
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->subject('Atur Ulang Kata Sandi')
            ->line('Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda.')
            ->action('Atur Ulang Kata Sandi', $url)
            ->line('Tautan ini hanya berlaku selama ' . config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') . ' menit.')
            ->line('Jika Anda tidak meminta pengaturan ulang kata sandi, harap abaikan email ini.')
            ->salutation('Terima kasih');
    }
}
