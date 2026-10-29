<footer class="ft" id="kontak">
    <div class="wrap">
        <div class="ftg">
            <div>
                @include('partials.logo', ['dark' => true])
                <p>Jl. Raya Waru No. 123, Waru, Sidoarjo, Jawa Timur 61256<br>
                Telp: {{ \App\Models\Setting::get('telepon', '-') }}<br>
                Email: {{ \App\Models\Setting::get('email', '-') }}</p>
            </div>
            <div>
                <h4>Tautan Cepat</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('home') }}#profil">Tentang Kami</a></li>
                    <li><a href="{{ route('home') }}#katalog">Katalog Produk</a></li>
                </ul>
            </div>
            <div>
                <h4>Ikuti Kami</h4>
                <p>Pantau info terbaru ketersediaan stok dan promo.</p>
            </div>
        </div>
        <div class="copy">© {{ date('Y') }} CV. Lingkar Mas. Hak cipta dilindungi.</div>
    </div>
</footer>
