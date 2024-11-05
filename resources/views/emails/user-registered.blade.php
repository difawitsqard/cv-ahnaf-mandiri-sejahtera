<!DOCTYPE html>
<html>

<head>
    <title>Selamat Datang di {{ getCompanyInfo()->name ?? config('app.name') }}</title>
</head>

<body>
    <h1>Selamat datang, {{ $user->name }}!</h1>
    <p>Anda telah ditunjuk sebagai <b>{{ ucwords($user->roles[0]->name) }}</b> di outlet <b>{{ $user->outlet->name }}</b>.</p>
    <p>Berikut adalah informasi akun Anda:
        <br>
        <strong>Email:</strong> {{ $user->email }}
        <br>
        <strong>Password:</strong> {{ url(route('password.reset', ['token' => $password])) }}
    </p>
    <p>Terima kasih telah bergabung dengan kami. Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami.</p>
</body>

</html>
