@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
<h1>Pengaturan perusahaan</h1>
<p class="mu">Data ini tampil di halaman klien (footer, tombol WhatsApp, logo) dan di laporan PDF.</p>

<div class="panel">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h3>Identitas perusahaan</h3>
        <div class="fgrid">
            <div>
                <label for="telepon">Nomor telepon / WhatsApp</label>
                <input id="telepon" name="telepon" value="{{ old('telepon', $telepon) }}" placeholder="0812xxxxxxx">
            </div>
            <div>
                <label for="email">Email perusahaan</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}">
            </div>
            <div class="full">
                <label for="logo">Logo perusahaan (JPG, PNG, WEBP, maks. 2 MB)</label>
                <input id="logo" name="logo" type="file" accept="image/png,image/jpeg,image/webp">
            </div>
        </div>

        @if ($logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo))
            <div class="logo-prev">
                <img src="{{ asset('storage/' . $logo) }}" alt="Logo saat ini">
                <label style="margin:0;font-weight:500"><input type="checkbox" name="hapus_logo" value="1" style="width:auto"> Hapus logo (kembali ke logo bawaan)</label>
            </div>
        @endif

        <h3 style="margin-top:26px">Tanda tangan laporan PDF</h3>
        <div class="fgrid">
            <div>
                <label for="ttd_nama">Nama penandatangan</label>
                <input id="ttd_nama" name="ttd_nama" value="{{ old('ttd_nama', $ttd_nama) }}" placeholder="Nama lengkap">
            </div>
            <div>
                <label for="ttd_jabatan">Jabatan</label>
                <input id="ttd_jabatan" name="ttd_jabatan" value="{{ old('ttd_jabatan', $ttd_jabatan) }}" placeholder="Direktur / Admin Gudang">
            </div>
            <div class="full">
                <label for="ttd">Gambar tanda tangan (PNG atau JPG, maks. 2 MB)</label>
                <input id="ttd" name="ttd" type="file" accept="image/png,image/jpeg">
                <p class="mu" style="margin-top:6px">Disarankan PNG berlatar transparan, tanda tangan hitam di atas kertas putih yang sudah dipotong rapi.</p>
            </div>
        </div>

        @if ($ttd && \Illuminate\Support\Facades\Storage::disk('local')->exists($ttd))
            <div class="logo-prev">
                <span class="st Dikirim">Tanda tangan sudah diunggah</span>
                <label style="margin:0;font-weight:500"><input type="checkbox" name="hapus_ttd" value="1" style="width:auto"> Hapus tanda tangan</label>
            </div>
        @endif

        <div class="row" style="margin-top:18px">
            <button class="btn" type="submit">Simpan perubahan</button>
        </div>
    </form>
</div>
@endsection
