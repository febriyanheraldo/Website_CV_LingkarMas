@extends('layouts.admin')
@section('title', $product->nama)

@section('content')
<div style="margin-bottom:14px;">
    @include('partials.back', ['url' => route('admin.products'), 'label' => 'Kembali ke daftar produk'])
</div>

<div class="head-row" style="align-items:center; margin-bottom:20px;">
    <div style="min-width:0; flex:1;">
        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:4px;">
            <h1 style="margin:0; font-size:clamp(20px, 3vw, 26px); word-break:break-word;">{{ $product->nama }}</h1>
            <span style="display:inline-block; font-size:12px; font-weight:600; padding:3px 10px; border-radius:99px; background:#e0f2fe; color:#0369a1;">
                {{ $product->kategori }}
            </span>
        </div>
        <p class="mu" style="margin:0; font-size:13px;">Kode Produk: <strong style="font-family:monospace; color:var(--tx);">{{ $product->kode_produk }}</strong></p>
    </div>
    <div class="row" style="flex-shrink:0;">
        <a class="btn sm line" href="{{ route('admin.products.edit', $product) }}" style="display:inline-flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            <span>Ubah</span>
        </a>
        <form class="inline" method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini? Riwayat pesanan tetap tersimpan.')">
            @csrf @method('DELETE')
            <button class="btn sm line" type="submit" style="color:#dc2626; border-color:#fca5a5; display:inline-flex; align-items:center; gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                <span>Hapus</span>
            </button>
        </form>
    </div>
</div>

<div class="pd">
    <div class="panel photo" style="border-radius:14px; overflow:hidden; background:#fff; border:1px solid var(--bd); position:relative;">
        @if ($product->foto_url)
            <img src="{{ $product->foto_url }}" alt="{{ $product->nama }}" style="width:100%; height:100%; object-fit:cover; display:block; max-height:360px;">
        @else
            <div style="height:220px; display:grid; place-items:center; background:#f8fafc; font-size:64px;">
                {{ $product->ikon }}
            </div>
        @endif
    </div>

    <div>
        <div class="stats" style="grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:12px; margin-bottom:14px;">
            <div class="stat" style="border-radius:12px; border-left:4px solid var(--or); padding:14px 16px;">
                <span style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">🏷️ Harga Satuan</span>
                <b style="font-size:clamp(18px, 2.5vw, 22px); color:#0b2239; margin-top:4px;">{{ $product->harga_rp }}</b>
            </div>
            <div class="stat" style="border-radius:12px; border-left:4px solid {{ $product->stok <= 5 ? '#ef4444' : '#22c55e' }}; padding:14px 16px;">
                <span style="font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">📦 Stok Gudang</span>
                <b style="font-size:clamp(18px, 2.5vw, 22px); color:#0b2239; margin-top:4px;">
                    {{ $product->stok }} <span style="font-size:13px; font-weight:500; color:var(--mu);">unit</span>
                </b>
                @if ($product->stok <= 5)
                    <div><span class="low-badge" style="font-size:11px;">⚠️ Stok menipis</span></div>
                @endif
            </div>
        </div>

        <div class="panel" style="border-radius:14px; margin-bottom:14px;">
            <h3 style="font-size:15px; font-weight:700; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
                <span>📋</span> Spesifikasi Teknis
            </h3>
            <dl class="dgrid" style="row-gap:8px; font-size:13px;">
                @forelse (array_filter(explode("\n", (string) $product->spesifikasi)) as $line)
                    @php [$k, $v] = array_pad(explode(':', $line, 2), 2, ''); @endphp
                    <dt style="font-weight:600; color:var(--mu);">{{ trim($k) }}</dt>
                    <dd style="color:var(--tx); word-break:break-word;">{{ trim($v) }}</dd>
                @empty
                    <dd style="grid-column:1/-1; color:var(--mu);">Belum ada spesifikasi tertulis.</dd>
                @endforelse
            </dl>
        </div>

        {{-- ═══ BARCODE PRODUK (Code 39 dengan Milon Barcode) ═══ --}}
        <div class="panel" style="border-radius:14px; padding:20px; background:#ffffff; border:1px solid var(--bd);">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:6px;">
                <div>
                    <h3 style="margin:0; font-size:15px; font-weight:700; display:flex; align-items:center; gap:6px;">
                        <span>🏷️</span> Barcode Inventaris (Code 39)
                    </h3>
                    <p style="color:#64748b; font-size:12px; margin:2px 0 0;">
                        Identitas visual untuk scanner gudang dan label fisik
                    </p>
                </div>
                <button type="button" class="btn sm line" onclick="window.print()" style="cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    <span>Cetak Label</span>
                </button>
            </div>

            <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; background:#f8fafc; padding:20px 14px; border-radius:12px; border:1px dashed #cbd5e1; margin-top:14px;">
                <div style="font-size:11px; font-weight:700; color:#64748b; letter-spacing:1px; text-transform:uppercase; margin-bottom:10px; text-align:center;">
                    {{ $product->nama }}
                </div>

                {{-- Barcode responsive wrapper --}}
                <div style="overflow-x:auto; max-width:100%; display:flex; justify-content:center; padding:6px 0;">
                    <div style="background:#fff; padding:10px 14px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
                        {!! $product->barcodeHtml(2, 65) !!}
                    </div>
                </div>

                <div style="margin-top:10px; font-family:monospace; font-size:15px; font-weight:700; letter-spacing:2.5px; color:#0f172a;">
                    {{ $product->kode_produk }}
                </div>
            </div>
        </div>
        {{-- ═══ END BARCODE PRODUK ═══ --}}
    </div>
</div>

@if ($product->video_url)
    <div class="panel" style="border-radius:14px; margin-top:14px;">
        <h3 style="font-size:15px; font-weight:700; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
            <span>🎥</span> Video Produk
        </h3>
        <div style="max-width:100%; overflow:hidden; border-radius:10px;">
            <video class="pvideo" src="{{ $product->video_url }}" controls preload="metadata" style="width:100%; max-height:400px; display:block;"></video>
        </div>
    </div>
@endif

<div class="panel" style="border-radius:14px; margin-top:14px;">
    <h3 style="font-size:15px; font-weight:700; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
        <span>📦</span> Riwayat Pesanan Produk Ini
    </h3>
    <div class="tw">
        <table>
            <thead>
                <tr>
                    <th>Kode Pesanan</th>
                    <th>Tanggal</th>
                    <th>Klien</th>
                    <th>Jumlah</th>
                    <th>Total Pembayaran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $o)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $o) }}" style="font-family:monospace; font-weight:600; color:var(--or);">
                                {{ $o->kode }}
                            </a>
                        </td>
                        <td style="color:var(--mu); font-size:12px;">{{ $o->tanggal }}</td>
                        <td style="font-weight:500;">{{ $o->user?->nama ?? 'Akun dihapus' }}</td>
                        <td>{{ $o->jumlah }} unit</td>
                        <td style="font-weight:600;">{{ $o->total_rp }}</td>
                        <td><span class="st {{ $o->status->value }}">{{ $o->status->value }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:24px; color:var(--mu);">
                            Belum ada riwayat pesanan untuk produk ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
