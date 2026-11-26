<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/logo/logo_lingkarmas.png') }}">
    <title>@yield('title', 'CV. Lingkar Mas')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700&family=Inter:wght@400;500;600&display=swap">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @include('partials.navbar')
    @yield('content')       
    @include('partials.footer')
    <script>
    // Hamburger menu publik (mobile)
    (function () {
        var btn = document.getElementById('navToggle');
        var menu = document.getElementById('mobMenu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = menu.classList.toggle('open');
            btn.setAttribute('aria-expanded', open);
            btn.textContent = open ? '✕' : '☰';
        });
        document.addEventListener('click', function (e) {
            if (!menu.contains(e.target) && e.target !== btn) {
                menu.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
                btn.textContent = '☰';
            }
        });
        // Tutup saat klik link
        menu.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', function () {
                menu.classList.remove('open');
                btn.textContent = '☰';
            });
        });
    })();
    </script>
</body>
</html>
