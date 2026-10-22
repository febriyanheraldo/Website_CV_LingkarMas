<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/logo/logo_lingkarmas.png') }}">
    <title>@yield('title') | Admin Lingkar Mas</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700&family=Inter:wght@400;500;600&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="adm">

    <aside class="side">
        @include('partials.logo', ['dark' => true, 'text' => 'Lingkar Mas'])
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'on' : '' }}">Dashboard</a>
        <a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products*') ? 'on' : '' }}">Produk</a>
        <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'on' : '' }}">Pesanan</a>
        <a href="{{ route('admin.clients') }}" class="{{ request()->routeIs('admin.clients*') ? 'on' : '' }}">Klien</a>

        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Yakin ingin keluar?')">
            @csrf
            <button type="submit" class="logout">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Keluar
            </button>
        </form>
    </aside>

    <main class="main">
        @yield('content')
    </main>

</div>

<script>
    // Tombol aksi hanya demo pada tahap frontend.
    document.querySelectorAll('[data-demo]').forEach(function (b) {
        b.addEventListener('click', function (e) {
            e.preventDefault();
            if (confirm(b.dataset.demo)) alert('Demo: aksi ini akan berfungsi setelah backend (Tahap 2) selesai.');
        });
    });
</script>
</body>
</html>
