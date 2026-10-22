@extends('layouts.admin')
@section('title', 'Tambah Produk')

@section('content')
@include('partials.back', ['url' => route('admin.products'), 'label' => 'Kembali ke daftar produk'])
<h1>Tambah produk</h1>
<p class="mu">Foto berformat JPG atau PNG, maksimal 2 MB.</p>

<div class="panel">
    <form method="POST" action="#" enctype="multipart/form-data" onsubmit="event.preventDefault();alert('Demo: form akan tersimpan setelah backend selesai.')">
        @csrf
        <div class="fgrid">
            <div><label for="nama">Nama produk</label><input id="nama" name="nama" placeholder="Industrial Pump"></div>
            <div>
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori"><option>Pompa &amp; Blower</option><option>Komponen Mesin</option></select>
            </div>
            <div><label for="harga">Harga (Rp)</label><input id="harga" name="harga" type="number" min="0" placeholder="8500000"></div>
            <div><label for="stok">Stok</label><input id="stok" name="stok" type="number" min="0" placeholder="10"></div>
            <div class="full"><label for="spesifikasi">Spesifikasi</label><textarea id="spesifikasi" name="spesifikasi" rows="4"></textarea></div>
            <div class="full"><label for="foto">Foto</label><input id="foto" name="foto" type="file" accept="image/png,image/jpeg"></div>
        </div>
        <div class="row" style="margin-top:18px">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn line" href="{{ route('admin.products') }}">Batal</a>
        </div>
    </form>
</div>
@endsection
