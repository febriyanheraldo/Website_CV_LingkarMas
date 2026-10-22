<nav class="top">
    <div class="wrap">
        @include('partials.logo')
        <div class="links">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('home') }}#profil">Profil</a>
            <a href="{{ route('home') }}#katalog">Katalog Produk</a>
            <a href="#kontak">Kontak</a>
        </div>
        <div class="navbtn">
            <a class="btn line sm" href="{{ route('login') }}">Login</a>
            <a class="btn sm" href="{{ route('home') }}#unduh">Unduh Aplikasi Android</a>
        </div>
    </div>
</nav>
