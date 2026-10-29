@extends('layouts.admin')
@section('title', $user->nama)

@section('content')
@include('partials.back', ['url' => route('admin.clients'), 'label' => 'Kembali ke daftar klien'])
<h1>{{ $user->nama }}</h1>
<p class="mu">{{ $user->email }} · {{ $user->hp ?? '-' }}</p>

<div class="panel">
    <h3>Riwayat pesanan</h3>
    <div class="tw">
        <table>
            <tr><th>Kode</th><th>Tanggal</th><th>Produk</th><th>Jml</th><th>Total</th><th>Status</th></tr>
            @forelse ($orders as $o)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $o) }}">{{ $o->kode }}</a></td>
                    <td>{{ $o->tanggal }}</td><td>{{ $o->produk_nama }}</td><td>{{ $o->jumlah }}</td>
                    <td>{{ $o->total_rp }}</td>
                    <td><span class="st {{ $o->status->value }}">{{ $o->status->value }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada pesanan.</td></tr>
            @endforelse
        </table>
    </div>
</div>
@endsection
