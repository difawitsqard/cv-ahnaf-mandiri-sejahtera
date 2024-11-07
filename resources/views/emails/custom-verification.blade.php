<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Verifikasi Alamat Email</title>
</head>

<body>
    <h2>Verifikasi Alamat Email Anda</h2>

    <p>Halo, <b>{{ $notifiable->name }}</b>!</p>
    <p>Anda telah ditunjuk sebagai
        <b>{{ isset($notifiable->roles[0]) ? ucwords($notifiable->roles[0]->name) : 'Unknown' }}</b>, di outlet
        <b>{{ $notifiable->outlet->name }}</b>.
        <br>Untuk menyelesaikan pendaftaran, silakan klik tombol di bawah untuk memverifikasi alamat email Anda:
    </p>
    <p>
        <a href="{{ $url }}">VERIFIKASI ALAMAT EMAIL</a>
    </p>

    <p>Salam, {{ auth()->user()->name }}<br>{{ getCompanyInfo()->name ?? config('mail.from.name') }}</p>
</body>

</html>
