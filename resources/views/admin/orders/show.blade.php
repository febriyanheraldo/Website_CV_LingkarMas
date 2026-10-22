@extends('layouts.admin')
@section('title', 'Detail Pesanan')

@php
    $flow = ['Menunggu', 'Diproses', 'Dikirim'];
    $idx = array_search($o['status'], $flow);
    $next = match ($o['status']) {
        'Menunggu' => [['Proses', 'Proses pesanan ini?'], ['Tolak', 'Tolak pesanan ini? Stok akan dikembalikan.']],
        'Diproses' => [['Kirim', 'Tandai pesanan sudah dikirim?'], ['Tolak', 'Tolak pesanan ini? Stok akan dikembalikan.']],
        default => [],
    };
@endphp

@section('content')
@include('partials.back', ['url' => route('admin.orders'), 'label' => 'Kembali ke daftar pesanan'])
<p class="mu"><a href="{{ route('admin.orders') }}">← Kembali ke daftar pesanan</a></p>
<h1>Pesanan {{ \App\Support\DummyData::kode($o['id']) }}</h1>
<p class="mu">Dibuat {{ $o['tanggal'] }}</p>

<div class="two">
    <div class="panel">
        <h3>Rincian</h3>
        <dl class="dgrid">
            <dt>Klien</dt><dd>{{ $o['klien'] }}</dd>
            <dt>Produk</dt><dd>{{ $o['produk'] }}</dd>
            <dt>Jumlah</dt><dd>{{ $o['jumlah'] }}</dd>
            <dt>Alamat</dt><dd>{{ $o['alamat'] }}</dd>
            <dt>Total</dt><dd><b>{{ \App\Support\DummyData::rp($o['total']) }}</b></dd>
            <dt>Status</dt><dd><span class="st {{ $o['status'] }}">{{ $o['status'] }}</span></dd>
        </dl>
    </div>
    <div class="panel">
        <h3>Status pesanan</h3>
        @if ($o['status'] === 'Ditolak')
            <div class="tl"><div class="done">Menunggu</div><div class="bad done">Ditolak</div></div>
        @else
            <div class="tl">
                @foreach ($flow as $i => $s)
                    <div class="{{ $i <= $idx ? 'done' : '' }}">{{ $s }}</div>
                @endforeach
            </div>
        @endif
        <div class="row" style="margin-top:16px">
            @forelse ($next as [$label, $msg])
                <button class="btn sm {{ $label === 'Tolak' ? 'line' : '' }}" data-demo="{{ $msg }}">{{ $label }}</button>
            @empty
                <span class="mu">Status akhir, tidak ada aksi lanjutan.</span>
            @endforelse
        </div>
    </div>
</div>
@endsection
