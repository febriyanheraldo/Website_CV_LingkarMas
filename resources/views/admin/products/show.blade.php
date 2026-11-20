@extends('layouts.admin')
@section('title', $product->nama)

@section('content')
<style>
    /* Styling responsif khusus halaman detail produk */
    .product-detail-wrap {
        width: 100%;
        max-width: 100%;
        min-width: 0;
    }
    .product-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .product-grid {
        display: grid;
        grid-template-columns: 320px minmax(0, 1fr);
        gap: 18px;
        margin-bottom: 20px;
        align-items: start;
    }
    .product-photo-box {
        background: #fff;
        border: 1px solid var(--bd);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .product-photo-img {
        width: 100%;
        height: auto;
        aspect-ratio: 4/3;
        max-height: 320px;
        object-fit: cover;
        display: block;
    }
    .product-info-col {
        min-width: 0;
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .product-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 12px;
    }
    .spec-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .spec-table tr {
        border-bottom: 1px solid #f1f5f9;
    }
    .spec-table tr:last-child {
        border-bottom: none;
    }
    .spec-table td {
        padding: 9px 8px;
        vertical-align: top;
    }
    .spec-label {
        color: #64748b;
        font-weight: 600;
        width: 36%;
        min-width: 100px;
    }
    .spec-val {
        color: #0f172a;
        font-weight: 500;
        word-break: break-word;
    }
    
    /* Barcode container responsif */
    .barcode-card {
        background: #ffffff;
        border: 1px solid var(--bd);
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .barcode-display-zone {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        padding: 18px 12px;
        margin-top: 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        overflow: hidden;
    }
    .barcode-scroll-area {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding: 6px 0;
        display: flex;
        justify-content: center;
    }
    .barcode-tag-container {
        display: inline-block;
        background: #fff;
        padding: 10px 14px;
        border-radius: 10px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        max-width: 100%;
        transition: transform 0.2s ease;
    }

    /* Penyesuaian layar tablet & mobile */
    @media (max-width: 860px) {
        .product-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .product-photo-img {
            max-height: 280px;
        }
    }
    @media (max-width: 520px) {
        .product-head {
            flex-direction: column;
            gap: 12px;
        }
        .product-head .row {
            width: 100%;
            display: flex;
        }
        .product-head .row .btn,
        .product-head .row form {
            flex: 1;
        }
        .product-head .row form button {
            width: 100%;
            justify-content: center;
        }
        .spec-table td {
            display: block;
            width: 100%;
            padding: 4px 6px;
        }
        .spec-table tr {
            padding: 8px 0;
            display: block;
        }
        .spec-label {
            padding-bottom: 2px;
            font-size: 12px;
        }
        .barcode-tag-container {
            transform: scale(0.9);
            transform-origin: center center;
        }
    }
    @media (max-width: 380px) {
        .barcode-tag-container {
            transform: scale(0.78);
            transform-origin: center center;
        }
    }
</style>

<div class="product-detail-wrap">
    <div style="margin-bottom:14px;">
        @include('partials.back', ['url' => route('admin.products'), 'label' => 'Kembali ke daftar produk'])
    </div>

    <!-- Header Nama Produk & Tombol Aksi -->
    <div class="product-head">
        <div style="min-width:0; flex:1;">
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:6px;">
                <h1 style="margin:0; font-size:clamp(20px, 4vw, 26px); font-weight:700; color:#0b2239; word-break:break-word;">
                    {{ $product->nama }}
                </h1>
                <span style="font-size:12px; font-weight:600; padding:3px 10px; border-radius:99px; background:#e0f2fe; color:#0369a1; white-space:nowrap;">
                    {{ $product->kategori }}
                </span>
            </div>
            <p class="mu" style="margin:0; font-size:13px;">
                Kode Referensi: <strong style="font-family:monospace; color:var(--tx); letter-spacing:0.5px;">{{ $product->kode_produk }}</strong>
            </p>
        </div>

        <div class="row" style="flex-shrink:0; gap:8px;">
            <a class="btn sm line" href="{{ route('admin.products.edit', $product) }}" style="display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                <span>Ubah</span>
            </a>
            <form class="inline" method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini? Riwayat pesanan tetap tersimpan.')">
                @csrf @method('DELETE')
                <button class="btn sm line" type="submit" style="color:#dc2626; border-color:#fca5a5; display:inline-flex; align-items:center; justify-content:center; gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    <span>Hapus</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Grid Utama: Foto di Kiri, Info & Barcode di Kanan -->
    <div class="product-grid">
        <!-- Kolom Foto -->
        <div class="product-photo-box">
            @if ($product->foto_url)
                <img class="product-photo-img" src="{{ $product->foto_url }}" alt="{{ $product->nama }}">
            @else
                <div style="height:220px; display:grid; place-items:center; background:#f8fafc; font-size:64px;">
                    {{ $product->ikon }}
                </div>
            @endif
        </div>

        <!-- Kolom Informasi Produk -->
        <div class="product-info-col">
            <!-- Harga & Stok -->
            <div class="product-stats-grid">
                <div class="stat" style="border-radius:14px; border-left:4px solid var(--or); padding:14px 16px; background:#fff; border-top:1px solid var(--bd); border-right:1px solid var(--bd); border-bottom:1px solid var(--bd);">
                    <span style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">🏷️ Estimasi Harga</span>
                    <b style="font-size:clamp(17px, 2.5vw, 22px); color:#0b2239; margin-top:4px; display:block;">{{ $product->harga_rp }}</b>
                </div>
                <div class="stat" style="border-radius:14px; border-left:4px solid {{ $product->stok <= 5 ? '#ef4444' : '#22c55e' }}; padding:14px 16px; background:#fff; border-top:1px solid var(--bd); border-right:1px solid var(--bd); border-bottom:1px solid var(--bd);">
                    <span style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">📦 Status Stok</span>
                    <b style="font-size:clamp(17px, 2.5vw, 22px); color:#0b2239; margin-top:4px; display:block;">
                        {{ $product->stok }} <span style="font-size:13px; font-weight:500; color:#64748b;">unit</span>
                    </b>
                    @if ($product->stok <= 5)
                        <div><span class="low-badge" style="font-size:11px;">⚠️ Stok Menipis</span></div>
                    @endif
                </div>
            </div>

            <!-- Panel Spesifikasi Teknis -->
            <div class="panel" style="border-radius:14px; padding:18px 20px; margin-bottom:0; background:#fff;">
                <h3 style="font-size:15px; font-weight:700; margin-bottom:12px; display:flex; align-items:center; gap:8px; color:#0b2239;">
                    <span>📋</span> Spesifikasi Teknis
                </h3>
                <table class="spec-table">
                    <tbody>
                        @forelse (array_filter(explode("\n", (string) $product->spesifikasi)) as $line)
                            @php [$k, $v] = array_pad(explode(':', $line, 2), 2, ''); @endphp
                            <tr>
                                <td class="spec-label">{{ trim($k) }}</td>
                                <td class="spec-val">{{ trim($v) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="color:#94a3b8; text-align:center; padding:12px 0;">Belum ada spesifikasi tertulis.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Panel Barcode Produk (Code 39) -->
            <div class="barcode-card">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:4px;">
                    <div>
                        <h3 style="margin:0; font-size:15px; font-weight:700; display:flex; align-items:center; gap:6px; color:#0b2239;">
                            <span>🏷️</span> Barcode Produk (Code 39)
                        </h3>
                        <p style="color:#64748b; font-size:12px; margin:2px 0 0;">
                            Identitas visual mesin untuk label inventaris gudang
                        </p>
                    </div>
                    <button type="button" class="btn sm line" onclick="window.print()" style="cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        <span>Cetak Label</span>
                    </button>
                </div>

                <div class="barcode-display-zone">
                    <div style="font-size:11px; font-weight:700; color:#64748b; letter-spacing:1px; text-transform:uppercase; margin-bottom:10px; max-width:90%; word-break:break-word;">
                        {{ $product->nama }}
                    </div>

                    <!-- Scroll Area Barcode Responsif -->
                    <div class="barcode-scroll-area">
                        <div class="barcode-tag-container">
                            {!! $product->barcodeHtml(2, 65) !!}
                        </div>
                    </div>

                    <div style="margin-top:10px; font-family:monospace; font-size:15px; font-weight:700; letter-spacing:2.5px; color:#0f172a;">
                        {{ $product->kode_produk }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Produk (Jika Ada) -->
    @if ($product->video_url)
        <div class="panel" style="border-radius:16px; padding:20px; margin-bottom:20px; background:#fff;">
            <h3 style="font-size:15px; font-weight:700; margin-bottom:12px; display:flex; align-items:center; gap:8px; color:#0b2239;">
                <span>🎥</span> Video Demonstrasi Produk
            </h3>
            <div style="max-width:100%; overflow:hidden; border-radius:12px; background:#000;">
                <video class="pvideo" src="{{ $product->video_url }}" controls preload="metadata" style="width:100%; max-height:420px; display:block; margin:0 auto;"></video>
            </div>
        </div>
    @endif

    <!-- Riwayat Pre-Order Produk Ini -->
    <div class="panel" style="border-radius:16px; padding:20px; background:#fff; margin-bottom:0;">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; margin-bottom:14px;">
            <div>
                <h3 style="font-size:15px; font-weight:700; margin:0; display:flex; align-items:center; gap:8px; color:#0b2239;">
                    <span>📦</span> Riwayat Pre-Order (PO) Produk Ini
                </h3>
                <p style="color:#64748b; font-size:12px; margin:2px 0 0;">
                    Daftar pengajuan pemesanan dari klien untuk item ini
                </p>
            </div>
        </div>

        <div class="tw" style="width:100%; max-width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch;">
            <table style="min-width:540px;">
                <thead>
                    <tr>
                        <th>No. Pre-Order</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Klien Pemesan</th>
                        <th>Jumlah</th>
                        <th>Estimasi Nilai</th>
                        <th>Status PO</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $o)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $o) }}" style="font-family:monospace; font-weight:700; color:var(--or);">
                                    {{ $o->kode }}
                                </a>
                            </td>
                            <td style="color:#64748b; font-size:12px;">{{ $o->tanggal }}</td>
                            <td style="font-weight:600; color:#0f172a;">{{ $o->user?->nama ?? 'Akun Dihapus' }}</td>
                            <td><strong style="color:#0f172a;">{{ $o->jumlah }}</strong> unit</td>
                            <td style="font-weight:600; color:#0f172a;">{{ $o->total_rp }}</td>
                            <td><span class="st {{ $o->status->value }}">{{ $o->status->value }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:30px; color:#94a3b8;">
                                Belum ada riwayat pre-order untuk produk ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
