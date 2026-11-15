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
    <a class="btn sm excel" href="{{ route('admin.laporan.pesanan.excel', request()->only(['q', 'status'])) }}">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><path d="M14 3v6h6M9 13l4 5M13 13l-4 5"/></svg>
    Export Excel
</a>
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
