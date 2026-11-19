<nav class="top">
    <div class="wrap">
        @include('partials.logo')
        <div class="links" id="pubLinks">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('home') }}#profil">Profil</a>
            <a href="{{ route('home') }}#katalog">Katalog Produk</a>
            <a href="#kontak">Kontak</a>
        </div>
        <div class="navbtn">
            @auth
                <a class="btn line sm nav-auth-btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <form class="inline nav-auth-btn" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn line sm" type="submit">Keluar</button>
                </form>
            @else
                <a class="btn line sm nav-login-btn" href="{{ route('login') }}">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                    <span>Login</span>
                </a>
            @endauth
            <a class="btn sm nav-dl-btn" href="{{ route('home') }}#unduh">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                <span class="dl-text">Unduh Aplikasi</span>
            </a>
            <button class="nav-toggle" id="navToggle" aria-label="Buka menu navigasi" aria-expanded="false">☰</button>
        </div>
    </div>
    <!-- Mobile dropdown menu -->
    <div class="mob-menu" id="mobMenu">
        <a href="{{ route('home') }}">Beranda</a>
        <a href="{{ route('home') }}#profil">Profil</a>
        <a href="{{ route('home') }}#katalog">Katalog Produk</a>
        <a href="#kontak">Kontak</a>
        <div class="mob-menu-actions">
            @auth
                <a class="btn sm" href="{{ route('admin.dashboard') }}" style="width:100%; justify-content:center;">Dashboard Admin</a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button class="btn line sm" type="submit" style="width:100%; justify-content:center;">Keluar</button>
                </form>
            @else
                <a class="btn line sm" href="{{ route('login') }}" style="width:100%; justify-content:center;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/></svg>
                    Login Admin
                </a>
            @endauth
            <a class="btn sm" href="{{ route('home') }}#unduh" style="width:100%; justify-content:center;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                Unduh Aplikasi Android
            </a>
        </div>
    </div>
</nav>
