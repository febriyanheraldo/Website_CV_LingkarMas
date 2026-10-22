@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h1>Dashboard Admin</h1>
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
                @foreach ($recent as $o)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $o['id']) }}">{{ \App\Support\DummyData::kode($o['id']) }}</a></td>
                        <td>{{ $o['klien'] }}</td>
                        <td>{{ $o['produk'] }}</td>
                        <td>{{ \App\Support\DummyData::rp($o['total']) }}</td>
                        <td><span class="st {{ $o['status'] }}">{{ $o['status'] }}</span></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="panel low">
        <h3>Stok menipis</h3>
        <ul>
            @forelse ($low as $p)
                <li>{{ $p['nama'] }} <b>{{ $p['stok'] }} unit</b></li>
            @empty
                <li>Semua stok aman.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
