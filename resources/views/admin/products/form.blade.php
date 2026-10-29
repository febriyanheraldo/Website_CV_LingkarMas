@extends('layouts.admin')
@section('title', $product->exists ? 'Ubah Produk' : 'Tambah Produk')

@section('content')
@include('partials.back', ['url' => route('admin.products'), 'label' => 'Kembali ke daftar produk'])
<h1>{{ $product->exists ? 'Ubah produk' : 'Tambah produk' }}</h1>
<p class="mu">Foto berformat JPG atau PNG, maksimal 2 MB.</p>

<div class="panel">
    <form method="POST" enctype="multipart/form-data"
          action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
        @csrf
        @if ($product->exists) @method('PUT') @endif

        <div class="fgrid">
            <div>
                <label for="nama">Nama produk</label>
                <input id="nama" name="nama" value="{{ old('nama', $product->nama) }}" placeholder="Industrial Pump">
            </div>
            <div>
                <label for="kategori">Kategori</label>
                <input id="kategori" name="kategori" list="kategori-list" value="{{ old('kategori', $product->kategori) }}" placeholder="Pompa &amp; Blower">
                <datalist id="kategori-list">
                    @foreach ($kategoris as $k) <option value="{{ $k }}"> @endforeach
                </datalist>
            </div>
            <div>
                <label for="harga">Harga (Rp)</label>
                <input id="harga" name="harga" type="number" min="0" value="{{ old('harga', $product->harga) }}">
            </div>
            <div>
                <label for="stok">Stok</label>
                <input id="stok" name="stok" type="number" min="0" value="{{ old('stok', $product->stok) }}">
            </div>
            <div class="full">
                <label for="spesifikasi">Spesifikasi (satu baris per item, format <i>Nama: nilai</i>)</label>
                <textarea id="spesifikasi" name="spesifikasi" rows="6">{{ old('spesifikasi', $product->spesifikasi) }}</textarea>
            </div>
            <div class="full">
                <label for="foto">Foto produk</label>
                <input id="foto" name="foto" type="file" accept="image/png,image/jpeg">
                @if ($product->foto_url)
                    <div class="logo-prev"><img src="{{ $product->foto_url }}" alt="Foto saat ini"><span class="mu">Foto saat ini. Pilih file baru untuk menggantinya.</span></div>
                @endif
            </div>
        </div>

        <div class="row" style="margin-top:18px">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn line" href="{{ route('admin.products') }}">Batal</a>
        </div>
    </form>
</div>
@endsection
