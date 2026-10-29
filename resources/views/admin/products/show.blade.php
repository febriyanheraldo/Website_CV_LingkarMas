@extends('layouts.admin')
@section('title', $product->nama)

@section('content')
@include('partials.back', ['url' => route('admin.products'), 'label' => 'Kembali ke daftar produk'])

<div class="head-row">
    <div>
        <h1>{{ $product->nama }}</h1>
        <p class="mu">{{ $product->kategori }}</p>
    </div>
    <div class="row">
        <a class="btn sm line" href="{{ route('admin.products.edit', $product) }}">Ubah</a>
        <form class="inline" method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini? Riwayat pesanan tetap tersimpan.')">
            @csrf @method('DELETE')
            <button class="btn sm line" type="submit">Hapus</button>
        </form>
    </div>
</div>

<div class="pd">
    <div class="panel photo">
        @if ($product->foto_url)
            <img src="{{ $product->foto_url }}" alt="{{ $product->nama }}">
        @else
            <span class="ph">{{ $product->ikon }}</span>
        @endif
    </div>

    <div>
        <div class="stats" style="grid-template-columns:1fr 1fr">
            <div class="stat"><span>Harga</span><b style="font-size:22px">{{ $product->harga_rp }}</b></div>
            <div class="stat">
                <span>Stok</span><b>{{ $product->stok }}</b>
                @if ($product->stok <= 5) <small class="low-badge">Stok menipis</small> @endif
            </div>
        </div>

        <div class="panel">
            <h3>Spesifikasi</h3>
            <dl class="dgrid">
                @forelse (array_filter(explode("\n", (string) $product->spesifikasi)) as $line)
                    @php [$k, $v] = array_pad(explode(':', $line, 2), 2, ''); @endphp
                    <dt>{{ trim($k) }}</dt><dd>{{ trim($v) }}</dd>
                @empty
                    <dd>Belum ada spesifikasi.</dd>
                @endforelse
            </dl>
        </div>
    </div>
</div>

<div class="panel">
    <h3>Pesanan produk ini</h3>
    <div class="tw">
        <table>
            <tr><th>Kode</th><th>Tanggal</th><th>Klien</th><th>Jml</th><th>Total</th><th>Status</th></tr>
            @forelse ($orders as $o)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $o) }}">{{ $o->kode }}</a></td>
                    <td>{{ $o->tanggal }}</td>
                    <td>{{ $o->user?->nama ?? 'Akun dihapus' }}</td>
                    <td>{{ $o->jumlah }}</td>
                    <td>{{ $o->total_rp }}</td>
                    <td><span class="st {{ $o->status->value }}">{{ $o->status->value }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada pesanan untuk produk ini.</td></tr>
            @endforelse
        </table>
    </div>
</div>
@endsection
