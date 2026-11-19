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

        {{-- ═══ BARCODE PRODUK (Sesuai PPT: Code 39 dengan Milon Barcode) ═══ --}}
        <div class="panel" style="padding:24px 20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                <h3 style="margin:0;">Barcode Produk</h3>
                <button type="button" class="btn sm line" onclick="window.print()" style="cursor:pointer;">
                    🖨️ Cetak Barcode
                </button>
            </div>
            <p style="color:#6b7280; font-size:13px; margin-bottom:18px;">
                Kode ID {{ $product->id }} dengan format Code 39 (C39)
            </p>

            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; background:#fff; padding:24px 16px; border-radius:10px; border:1px solid #e5e7eb;">
                {{-- Nama barang di atas barcode --}}
                <div style="font-size:12px; font-weight:700; color:#6b7280; letter-spacing:1.5px; text-transform:uppercase; margin-bottom:14px;">
                    {{ $product->nama }}    
                </div>

                {{-- Barcode Code 39 di tengah (width=2, height=70 agar pas di panel) --}}
                <div style="overflow-x:auto; max-width:100%; display:flex; justify-content:center; padding:4px 0;">
                    {!! $product->barcodeHtml(2, 70) !!}
                </div>

                {{-- Kode PRD-0000X di bawah barcode --}}
                <div style="margin-top:12px; font-family:monospace; font-size:16px; font-weight:700; letter-spacing:3px; color:#111827;">
                    {{ $product->kode_produk }}
                </div>
            </div>
        </div>
        {{-- ═══ END BARCODE PRODUK ═══ --}}

    </div>
</div>

    @if ($product->video_url)
        <div class="panel">
            <h3>Video produk</h3>
            <video class="pvideo" src="{{ $product->video_url }}" controls preload="metadata"></video>
        </div>
    @endif

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
