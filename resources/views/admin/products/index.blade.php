@extends('layouts.admin')
@section('title', 'Produk')

@section('content')
<h1>Produk</h1>
<p class="mu">Kelola katalog yang tampil di aplikasi mobile.</p>

<form class="tools" method="GET">
    <input name="q" value="{{ $q }}" placeholder="Cari produk" aria-label="Cari produk">
    <select name="kategori" aria-label="Kategori" onchange="this.form.submit()">
        <option value="">Semua kategori</option>
        @foreach (['Pompa & Blower', 'Komponen Mesin'] as $k)
            <option value="{{ $k }}" @selected($kat === $k)>{{ $k }}</option>
        @endforeach
    </select>
    <button class="btn sm" type="submit">Cari</button>
    <a class="btn sm" href="{{ route('admin.products.create') }}">Tambah produk</a>
</form>

<div class="panel">
    <div class="tw">
        <table>
            <tr><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
            @forelse ($items as $p)
                <tr>
                    <td>
                        <span class="mini">
                            @if (file_exists(public_path($p['foto'])))
                                <img src="{{ asset($p['foto']) }}" alt="">
                            @else
                                {{ $p['ikon'] }}
                            @endif
                        </span>
                        {{ $p['nama'] }}
                    </td>
                    <td>{{ $p['kategori'] }}</td>
                    <td>{{ \App\Support\DummyData::rp($p['harga']) }}</td>
                    <td>{{ $p['stok'] }}</td>
                    <td>
                        <a class="btn sm line" href="{{ route('admin.products.create') }}">Ubah</a>
                        <button class="btn sm line" data-demo="Hapus produk {{ $p['nama'] }}?">Hapus</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Produk tidak ditemukan.</td></tr>
            @endforelse
        </table>
    </div>
    <div class="pg"><span class="on">1</span><span>2</span><span>›</span></div>
</div>
@endsection
