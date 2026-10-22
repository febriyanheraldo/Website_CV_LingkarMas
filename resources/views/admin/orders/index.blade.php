@extends('layouts.admin')
@section('title', 'Pesanan')

@section('content')
<h1>Pesanan</h1>
<p class="mu">Ubah status sesuai alur: Menunggu, Diproses, Dikirim.</p>

<form class="tools" method="GET">
    <input name="q" value="{{ $q }}" placeholder="Cari kode atau nama klien" aria-label="Cari">
    <select name="status" aria-label="Status" onchange="this.form.submit()">
        <option value="">Semua status</option>
        @foreach (['Menunggu', 'Diproses', 'Dikirim', 'Ditolak'] as $s)
            <option @selected($status === $s)>{{ $s }}</option>
        @endforeach
    </select>
    <button class="btn sm" type="submit">Cari</button>
</form>

<div class="panel">
    <div class="tw">
        <table>
            <tr><th>Kode</th><th>Tanggal</th><th>Klien</th><th>Produk</th><th>Jml</th><th>Total</th><th>Status</th><th></th></tr>
            @forelse ($items as $o)
                <tr>
                    <td>{{ \App\Support\DummyData::kode($o['id']) }}</td>
                    <td>{{ $o['tanggal'] }}</td>
                    <td>{{ $o['klien'] }}</td>
                    <td>{{ $o['produk'] }}</td>
                    <td>{{ $o['jumlah'] }}</td>
                    <td>{{ \App\Support\DummyData::rp($o['total']) }}</td>
                    <td><span class="st {{ $o['status'] }}">{{ $o['status'] }}</span></td>
                    <td><a class="btn sm line" href="{{ route('admin.orders.show', $o['id']) }}">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="8">Pesanan tidak ditemukan.</td></tr>
            @endforelse
        </table>
    </div>
    <div class="pg"><span class="on">1</span><span>2</span><span>›</span></div>
</div>
@endsection
