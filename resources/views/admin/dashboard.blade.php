@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h1>Dashboard</h1>
<p class="mu">Ringkasan pesanan, klien, dan stok.</p>

<div class="stats">
    @foreach ($stat as $label => $n)
        <div class="stat"><span>{{ $label === 'Klien' ? 'Total klien' : $label }}</span><b>{{ $n }}</b></div>
    @endforeach
</div>

<div class="two">
    <div class="panel">
        <h3>Pesanan terbaru</h3>
        <div class="tw">
            <table>
                <tr><th>Kode</th><th>Klien</th><th>Produk</th><th>Total</th><th>Status</th></tr>
                @forelse ($recent as $o)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $o) }}">{{ $o->kode }}</a></td>
                        <td>{{ $o->user?->nama ?? 'Akun dihapus' }}</td>
                        <td>{{ $o->produk_nama }}</td>
                        <td>{{ $o->total_rp }}</td>
                        <td><span class="st {{ $o->status->value }}">{{ $o->status->value }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5">Belum ada pesanan.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
    <div class="panel low">
        <h3>Stok menipis</h3>
        <ul>
            @forelse ($low as $p)
                <li><a href="{{ route('admin.products.show', $p) }}">{{ $p->nama }}</a> <b>{{ $p->stok }} unit</b></li>
            @empty
                <li>Semua stok aman.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
