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
                <a class="btn wa" href="#kontak">Chat WA</a>
            </div>
        </div>
        <div class="art">
            <img src="{{ asset('images/produk/pump.jpg') }}" alt="Pompa dan blower industri CV. Lingkar Mas">
            <div class="rate"><b>Rating 4,6 ★</b><span>Ulasan Google Maps</span></div>
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
                        @if (file_exists(public_path($p['foto'])))
                            <img src="{{ asset($p['foto']) }}" alt="{{ $p['nama'] }}" loading="lazy">
                        @else
                            {{ $p['ikon'] }}
                        @endif
                    </div>
                    <span class="cat">{{ $p['kategori'] }}</span>
                    <h3>{{ $p['nama'] }}</h3>
                    <p>{{ $p['ringkas'] }}</p>
                    <div class="pr">{{ \App\Support\DummyData::rp($p['harga']) }}</div>
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
@endsection
