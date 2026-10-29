@extends('layouts.admin')
@section('title', 'Detail Pesanan')

@section('content')
@php
    $flow = ['Menunggu', 'Diproses', 'Dikirim'];
    $idx = array_search($order->status->value, $flow);
    $labels = ['Diproses' => 'Proses', 'Dikirim' => 'Kirim', 'Ditolak' => 'Tolak'];
    $confirm = [
        'Diproses' => 'Proses pesanan ini?',
        'Dikirim' => 'Tandai pesanan sudah dikirim?',
        'Ditolak' => 'Tolak pesanan ini? Stok akan dikembalikan.',
    ];
@endphp

@include('partials.back', ['url' => route('admin.orders'), 'label' => 'Kembali ke daftar pesanan'])
<h1>Pesanan {{ $order->kode }}</h1>
<p class="mu">Dibuat {{ $order->tanggal }}</p>

<div class="two">
    <div class="panel">
        <h3>Rincian</h3>
        <dl class="dgrid">
            <dt>Klien</dt><dd>{{ $order->user?->nama ?? 'Akun dihapus' }}</dd>
            <dt>Produk</dt><dd>{{ $order->produk_nama }}</dd>
            <dt>Jumlah</dt><dd>{{ $order->jumlah }}</dd>
            <dt>Alamat</dt><dd>{{ $order->alamat }}</dd>
            <dt>Total</dt><dd><b>{{ $order->total_rp }}</b></dd>
            <dt>Status</dt><dd><span class="st {{ $order->status->value }}">{{ $order->status->value }}</span></dd>
        </dl>
    </div>

    <div class="panel">
        <h3>Status pesanan</h3>
        @if ($order->status->value === 'Ditolak')
            <div class="tl"><div class="done">Menunggu</div><div class="bad done">Ditolak</div></div>
        @else
            <div class="tl">
                @foreach ($flow as $i => $s)
                    <div class="{{ $i <= $idx ? 'done' : '' }}">{{ $s }}</div>
                @endforeach
            </div>
        @endif

        <div class="row" style="margin-top:16px">
            @forelse ($order->status->nextStatuses() as $next)
                <form class="inline" method="POST" action="{{ route('admin.orders.status', $order) }}" onsubmit="return confirm('{{ $confirm[$next->value] }}')">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="{{ $next->value }}">
                    <button class="btn sm {{ $next->value === 'Ditolak' ? 'line' : '' }}" type="submit">{{ $labels[$next->value] }}</button>
                </form>
            @empty
                <span class="mu">Status akhir, tidak ada aksi lanjutan.</span>
            @endforelse
        </div>
    </div>
</div>
@endsection
