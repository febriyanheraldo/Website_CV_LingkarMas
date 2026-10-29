@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
<h1>Pengaturan perusahaan</h1>
<p class="mu">Data ini tampil di halaman klien (footer, tombol WhatsApp, dan logo).</p>

<div class="panel">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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

        <div class="row" style="margin-top:18px">
            <button class="btn" type="submit">Simpan perubahan</button>
        </div>
    </form>
</div>
@endsection
