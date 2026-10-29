@extends('layouts.admin')
@section('title', 'Pesanan')

@section('content')
@include('partials.back', ['url' => route('admin.dashboard'), 'label' => 'Kembali ke dashboard'])
<h1>Pesanan</h1>
<p class="mu">Ubah status sesuai alur: Menunggu, Diproses, Dikirim.</p>

<form class="tools" method="GET">
    <input name="q" value="{{ $q }}" placeholder="Cari kode atau nama klien" aria-label="Cari">
    <select name="status" aria-label="Status" onchange="this.form.submit()">
        <option value="">Semua status</option>
        @foreach (\App\Enums\OrderStatus::cases() as $s)
            <option value="{{ $s->value }}" @selected($status === $s->value)>{{ $s->value }}</option>
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
                    <td>{{ $o->kode }}</td>
                    <td>{{ $o->tanggal }}</td>
                    <td>{{ $o->user?->nama ?? 'Akun dihapus' }}</td>
                    <td>{{ $o->produk_nama }}</td>
                    <td>{{ $o->jumlah }}</td>
                    <td>{{ $o->total_rp }}</td>
                    <td><span class="st {{ $o->status->value }}">{{ $o->status->value }}</span></td>
                    <td><a class="btn sm line" href="{{ route('admin.orders.show', $o) }}">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="8">Pesanan tidak ditemukan.</td></tr>
            @endforelse
        </table>
    </div>
    {{ $items->links('partials.pagination') }}
</div>
@endsection
