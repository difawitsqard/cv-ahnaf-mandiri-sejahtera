<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;

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
            ->subject('Anda Telah Terdaftar')
            ->greeting('Selamat Datang, ' . $notifiable->name . '!')
            ->line("Anda telah ditunjuk sebagai $roleName di outlet {$notifiable->outlet->name}")
            ->line('Untuk menyelesaikan pendaftaran, silakan verifikasi alamat email Anda.')
            ->action('Verifikasi Alamat Email', $url)
            ->salutation('Salam Hormat, ' . auth()->user()->name);
    }
}
