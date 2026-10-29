@extends('layouts.public')

@section('content')
<header class="hero">
    <div class="wrap">
        <div>
            <span class="pill">Distributor Peralatan Industri</span>
            <h1>Penyedia Pompa, Blower &amp; Komponen Industri Terpercaya</h1>
            <p>Distributor pompa, roots blower, bearing, dan oil seal industri di Sidoarjo. Melayani kebutuhan pabrik, kontraktor, dan retail.</p>
            <div class="row">
                <a class="btn" href="#katalog">Lihat Katalog</a>
                <a class="btn wa" href="https://wa.me/{{ \App\Models\Setting::waNumber() }}" target="_blank" rel="noopener">Chat Sales WA</a>
            </div>
        </div>
        @php
            $slides = $products->filter(fn ($p) => $p->foto_url)->values();
        @endphp

        <div class="art slider" id="heroSlider" aria-roledescription="carousel" aria-label="Foto produk unggulan">
            @forelse ($slides as $i => $p)
                <figure class="slide {{ $i === 0 ? 'on' : '' }}">
                    <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}" @if ($i > 0) loading="lazy" @endif>
                    <figcaption>{{ $p->nama }}</figcaption>
                </figure>
            @empty
                <figure class="slide on">
                    <img src="{{ asset('images/produk/pump.jpg') }}" alt="Pompa dan blower industri CV. Lingkar Mas">
                </figure>
            @endforelse

            @if ($slides->count() > 1)
                <div class="dots">
                    @foreach ($slides as $i => $p)
                        <button type="button" class="{{ $i === 0 ? 'on' : '' }}" aria-label="Tampilkan {{ $p->nama }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</header>

<section class="sec" id="profil">
    <div class="wrap">
        <h2>Mengapa Pilih Kami?</h2>
        <p class="sub">Komitmen kami untuk memberikan kemudahan transaksi dan jaminan kualitas untuk menunjang produktivitas rantai pasok industri Anda.</p>
        <div class="grid3">
            <div class="card"><div class="ic">📊</div><h3>Stok Real-time</h3><p>Ketersediaan produk selalu langsung melalui aplikasi kami, sehingga rencana pembelian Anda lebih pasti.</p></div>
            <div class="card"><div class="ic">🛡</div><h3>Produk Original &amp; Garansi</h3><p>Semua komponen industri kami bersumber dari distributor resmi dan dilengkapi garansi produk.</p></div>
            <div class="card"><div class="ic">🚚</div><h3>Pengiriman Cepat</h3><p>Layanan logistik terintegrasi menjangkau pengiriman ke seluruh wilayah Jawa Timur.</p></div>
        </div>
    </div>
</section>

<section class="sec" id="katalog" style="background:var(--bg2)">
    <div class="wrap">
        <h2>Katalog Produk</h2>
        <p class="sub">Empat lini produk utama untuk kebutuhan mesin dan instalasi industri.</p>
        <div class="grid4">
            @foreach ($products as $p)
                <div class="card prod">
                    <div class="thumb">
                        @if ($p->foto_url)
                            <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}" loading="lazy">
                        @else
                            {{ $p->ikon }}
                        @endif
                    </div>
                    <span class="cat">{{ $p->kategori }}</span>
                    <h3>{{ $p->nama }}</h3>
                    <p>{{ $p->ringkas }}</p>
                    <div class="pr">{{ $p->harga_rp }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="sec" id="unduh">
    <div class="wrap">
        <div class="dl">
            @if (file_exists(public_path('images/produk.jpeg')))
                <div class="phone phone-real">
                    <img src="{{ asset('images/produk.jpeg') }}" alt="Tampilan aplikasi mobile CV. Lingkar Mas" loading="lazy">
                </div>
            @else
                <div class="phone"><b style="top:60px"></b><b style="top:114px"></b><b style="top:168px"></b></div>
            @endif
            <div>
                <h2>Unduh Aplikasi Kami</h2>
                <p>Akses katalog produk terlengkap, cek ketersediaan stok aktual, pantau status pesanan, dan lakukan transaksi langsung dari smartphone Anda.</p>
                <a class="btn" href="#">Unduh APK Android</a>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    var root = document.getElementById('heroSlider');
    if (!root) return;

    var slides = root.querySelectorAll('.slide');
    var dots = root.querySelectorAll('.dots button');
    if (slides.length < 2) return;

    var i = 0, timer = null;
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function show(n) {
        slides[i].classList.remove('on');
        if (dots[i]) dots[i].classList.remove('on');
        i = (n + slides.length) % slides.length;
        slides[i].classList.add('on');
        if (dots[i]) dots[i].classList.add('on');
    }

    function start() { if (!reduce) timer = setInterval(function () { show(i + 1); }, 4000); }
    function stop() { clearInterval(timer); }

    dots.forEach(function (d, n) {
        d.addEventListener('click', function () { stop(); show(n); start(); });
    });

    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
    start();
})();
</script>
@endsection
