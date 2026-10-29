@extends('layouts.admin')
@section('title', 'Produk')

@section('content')
@include('partials.back', ['url' => route('admin.dashboard'), 'label' => 'Kembali ke dashboard'])
<h1>Produk</h1>
<p class="mu">Kelola katalog yang tampil di aplikasi mobile.</p>

<form class="tools" method="GET">
    <input name="q" value="{{ $q }}" placeholder="Cari produk" aria-label="Cari produk">
    <select name="kategori" aria-label="Kategori" onchange="this.form.submit()">
        <option value="">Semua kategori</option>
        @foreach ($kategoris as $k)
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
                            @if ($p->foto_url) <img src="{{ $p->foto_url }}" alt=""> @else {{ $p->ikon }} @endif
                        </span>
                        <a href="{{ route('admin.products.show', $p) }}">{{ $p->nama }}</a>
                    </td>
                    <td>{{ $p->kategori }}</td>
                    <td>{{ $p->harga_rp }}</td>
                    <td>{{ $p->stok }}</td>
                    <td class="row">
                        <a class="btn sm" href="{{ route('admin.products.show', $p) }}">Detail</a>
                        <a class="btn sm line" href="{{ route('admin.products.edit', $p) }}">Ubah</a>
                        <form class="inline" method="POST" action="{{ route('admin.products.destroy', $p) }}" onsubmit="return confirm('Hapus produk ini? Riwayat pesanan tetap tersimpan.')">
                            @csrf @method('DELETE')
                            <button class="btn sm line" type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Produk tidak ditemukan.</td></tr>
            @endforelse
        </table>
    </div>
    {{ $items->links('partials.pagination') }}
</div>
@endsection
