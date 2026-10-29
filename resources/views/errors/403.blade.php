@extends('layouts.public')
@section('title', 'Akses ditolak')

@section('content')
<section class="sec">
    <div class="wrap" style="text-align:center;max-width:520px">
        <h1 style="font-size:64px;color:var(--or)">403</h1>
        <h2 style="font-size:24px;margin:6px 0 10px">Akses ditolak</h2>
        <p class="mu" style="margin-bottom:22px">Akun kamu tidak memiliki izin untuk membuka halaman ini.</p>
        <a class="btn" href="{{ route('home') }}">Kembali ke beranda</a>
    </div>
</section>
@endsection
