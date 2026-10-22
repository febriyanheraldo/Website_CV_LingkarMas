<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/logo/logo_lingkarmas.png') }}">
    <title>Login Admin | CV. Lingkar Mas</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700&family=Inter:wght@400;500;600&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="login">
    <div class="l">
        @include('partials.logo', ['dark' => true])
        <h2>Panel Admin</h2>
        <p>Kelola produk, proses pesanan klien, dan pantau stok dalam satu tempat.</p>
    </div>
    <div class="r">
        <form class="form" method="POST" action="{{ route('login.post') }}">
            @csrf
            <h1>Masuk</h1>
            <p class="mu">Khusus akun admin.</p>
            @if ($errors->any())
                <div class="err">{{ $errors->first() }}</div>
            @endif
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', 'admin@lingkarmas.com') }}" autocomplete="username">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" value="Admin#12345" autocomplete="current-password">
            <button class="btn" type="submit">Masuk</button>
            <div style="margin-top:16px">
                @include('partials.back', ['url' => route('home'), 'label' => 'Kembali ke beranda'])
            </div>
        </form>
    </div>
</div>
</body>
</html>
