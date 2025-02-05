<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomEmailVerificationNotification extends BaseVerifyEmail
{
    public function toMail($notifiable)
    {
        $roleName = isset($notifiable->roles[0]) ? ucwords($notifiable->roles[0]->name) : 'Unknown';

        $url = $this->verificationUrl($notifiable);
        // return (new MailMessage())
        //     ->from(config('mail.from.address'), getCompanyInfo()->name ?? config('mail.from.name'))
        //     ->subject('Anda Telah Terdaftar')
        //     ->view('emails.custom-verification', ['url' => $url, 'notifiable' => $notifiable]);

        return (new MailMessage())
            ->from(config('mail.from.address'), getCompanyInfo()->name ?? config('mail.from.name'))
            ->subject('Verifikasi Alamat Email Anda')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini.')
            ->action('Verifikasi Alamat Email', $url)
            ->salutation("Terima kasih");
    }
}
