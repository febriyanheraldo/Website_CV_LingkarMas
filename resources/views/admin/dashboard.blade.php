@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<!-- Welcome Banner Header -->
<div style="background: linear-gradient(135deg, #0b2239 0%, #173d63 100%); color:#fff; border-radius:18px; padding:28px 24px; margin-bottom:24px; position:relative; overflow:hidden; box-shadow: 0 10px 25px rgba(11,34,57,0.12);">
    <div style="position:absolute; right:-30px; top:-30px; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle, rgba(249,115,22,0.2) 0%, transparent 70%); pointer-events:none;"></div>
    
    <div style="position:relative; z-index:2; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
        <div>
            <div style="display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,0.12); padding:4px 12px; border-radius:99px; font-size:12px; font-weight:600; color:#fdba74; margin-bottom:10px;">
                <span>✨</span> Panel Operasional CV. Lingkar Mas
            </div>
            <h1 style="color:#fff; font-size:clamp(22px, 3vw, 28px); margin:0 0 6px; font-weight:700;">
                Selamat Datang, {{ auth()->user()->nama }} 👋
            </h1>
            <p style="color:#cbd5e1; margin:0; font-size:14px; max-width:60ch;">
                Kelola katalog produk, cetak barcode inventaris Code 39, dan pantau status transaksi klien secara real-time.
            </p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a class="btn sm" href="{{ route('admin.products.create') }}" style="box-shadow: 0 4px 12px rgba(249,115,22,0.35);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>Tambah Produk</span>
            </a>
            <a class="btn sm line" href="{{ route('admin.orders') }}" style="background:rgba(255,255,255,0.1); color:#fff; border-color:rgba(255,255,255,0.3);">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <span>Daftar Pesanan</span>
            </a>
        </div>
    </div>
</div>

<!-- Stat Cards (Modern Dashboard Cards) -->
<div class="stats" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px;">
    
    <!-- Card 1: Menunggu -->
    <a href="{{ route('admin.orders') }}" style="display:block; text-decoration:none; color:inherit;">
        <div class="stat-card" style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:20px; position:relative; overflow:hidden; transition:all 0.25s ease; box-shadow:0 4px 12px rgba(0,0,0,0.03); border-top:4px solid #f59e0b;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <span style="font-size:13px; font-weight:600; color:#64748b;">Pesanan Menunggu</span>
                <div style="width:38px; height:38px; border-radius:10px; background:#fef3c7; color:#d97706; display:grid; place-items:center; font-size:18px;">
                    ⏳
                </div>
            </div>
            <div style="display:flex; align-items:baseline; gap:8px;">
                <b style="font-size:32px; font-family:'Outfit',sans-serif; color:#0f172a; font-weight:700;">{{ $stat['Menunggu'] }}</b>
                <span style="font-size:13px; color:#64748b;">pesanan</span>
            </div>
            <div style="margin-top:10px; font-size:12px; color:#d97706; display:flex; align-items:center; gap:4px; font-weight:500;">
                <span>●</span> Perlu konfirmasi &amp; validasi
            </div>
        </div>
    </a>

    <!-- Card 2: Diproses -->
    <a href="{{ route('admin.orders') }}" style="display:block; text-decoration:none; color:inherit;">
        <div class="stat-card" style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:20px; position:relative; overflow:hidden; transition:all 0.25s ease; box-shadow:0 4px 12px rgba(0,0,0,0.03); border-top:4px solid #3b82f6;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <span style="font-size:13px; font-weight:600; color:#64748b;">Sedang Diproses</span>
                <div style="width:38px; height:38px; border-radius:10px; background:#dbeafe; color:#2563eb; display:grid; place-items:center; font-size:18px;">
                    ⚙️
                </div>
            </div>
            <div style="display:flex; align-items:baseline; gap:8px;">
                <b style="font-size:32px; font-family:'Outfit',sans-serif; color:#0f172a; font-weight:700;">{{ $stat['Diproses'] }}</b>
                <span style="font-size:13px; color:#64748b;">pesanan</span>
            </div>
            <div style="margin-top:10px; font-size:12px; color:#2563eb; display:flex; align-items:center; gap:4px; font-weight:500;">
                <span>●</span> Tahap penyiapan barang
            </div>
        </div>
    </a>

    <!-- Card 3: Dikirim -->
    <a href="{{ route('admin.orders') }}" style="display:block; text-decoration:none; color:inherit;">
        <div class="stat-card" style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:20px; position:relative; overflow:hidden; transition:all 0.25s ease; box-shadow:0 4px 12px rgba(0,0,0,0.03); border-top:4px solid #10b981;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <span style="font-size:13px; font-weight:600; color:#64748b;">Sedang Dikirim</span>
                <div style="width:38px; height:38px; border-radius:10px; background:#dcfce7; color:#16a34a; display:grid; place-items:center; font-size:18px;">
                    🚚
                </div>
            </div>
            <div style="display:flex; align-items:baseline; gap:8px;">
                <b style="font-size:32px; font-family:'Outfit',sans-serif; color:#0f172a; font-weight:700;">{{ $stat['Dikirim'] }}</b>
                <span style="font-size:13px; color:#64748b;">pesanan</span>
            </div>
            <div style="margin-top:10px; font-size:12px; color:#16a34a; display:flex; align-items:center; gap:4px; font-weight:500;">
                <span>●</span> Dalam perjalanan logistik
            </div>
        </div>
    </a>

    <!-- Card 4: Total Klien -->
    <a href="{{ route('admin.clients') }}" style="display:block; text-decoration:none; color:inherit;">
        <div class="stat-card" style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:20px; position:relative; overflow:hidden; transition:all 0.25s ease; box-shadow:0 4px 12px rgba(0,0,0,0.03); border-top:4px solid #8b5cf6;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
                <span style="font-size:13px; font-weight:600; color:#64748b;">Total Klien</span>
                <div style="width:38px; height:38px; border-radius:10px; background:#f3e8ff; color:#9333ea; display:grid; place-items:center; font-size:18px;">
                    👥
                </div>
            </div>
            <div style="display:flex; align-items:baseline; gap:8px;">
                <b style="font-size:32px; font-family:'Outfit',sans-serif; color:#0f172a; font-weight:700;">{{ $stat['Klien'] }}</b>
                <span style="font-size:13px; color:#64748b;">pengguna</span>
            </div>
            <div style="margin-top:10px; font-size:12px; color:#9333ea; display:flex; align-items:center; gap:4px; font-weight:500;">
                <span>●</span> Akun pembeli aktif
            </div>
        </div>
    </a>
</div>

<!-- Two Columns Layout: Pesanan Terbaru & Stok Menipis -->
<div class="two" style="gap:20px;">
    
    <!-- Kolom Kiri: Pesanan Terbaru -->
    <div class="panel" style="border-radius:16px; padding:22px; box-shadow:0 4px 14px rgba(0,0,0,0.03); border:1px solid #e2e8f0; margin-bottom:0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:8px;">
            <div>
                <h3 style="margin:0; font-size:17px; font-weight:700; color:#0b2239; display:flex; align-items:center; gap:8px;">
                    <span>📋</span> Pesanan Terbaru
                </h3>
                <p style="color:#64748b; font-size:13px; margin:2px 0 0;">5 transaksi terakhir yang masuk ke sistem</p>
            </div>
            <a href="{{ route('admin.orders') }}" style="font-size:13px; font-weight:600; color:var(--or); display:inline-flex; align-items:center; gap:4px;">
                <span>Lihat Semua</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </div>

        <div class="tw">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Klien</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent as $o)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $o) }}" style="font-family:monospace; font-weight:700; color:var(--or);">
                                    {{ $o->kode }}
                                </a>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span style="width:26px; height:26px; border-radius:50%; background:#e2e8f0; color:#475569; display:inline-grid; place-items:center; font-size:11px; font-weight:700;">
                                        {{ strtoupper(substr($o->user?->nama ?? 'A', 0, 1)) }}
                                    </span>
                                    <span>{{ $o->user?->nama ?? 'Akun dihapus' }}</span>
                                </div>
                            </td>
                            <td style="color:#334155; font-weight:500;">{{ $o->produk_nama }}</td>
                            <td style="font-weight:600; color:#0f172a;">{{ $o->total_rp }}</td>
                            <td><span class="st {{ $o->status->value }}">{{ $o->status->value }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:30px; color:#94a3b8;">
                                Belum ada transaksi pesanan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kolom Kanan: Peringatan Stok Menipis -->
    <div class="panel low" style="border-radius:16px; padding:22px; box-shadow:0 4px 14px rgba(0,0,0,0.03); border:1px solid #e2e8f0; margin-bottom:0;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <div>
                <h3 style="margin:0; font-size:17px; font-weight:700; color:#0b2239; display:flex; align-items:center; gap:8px;">
                    <span>⚠️</span> Peringatan Stok
                </h3>
                <p style="color:#64748b; font-size:13px; margin:2px 0 0;">Stok &le; 5 unit perlu restock</p>
            </div>
            @if ($low->count() > 0)
                <span style="background:#fee2e2; color:#dc2626; font-size:12px; font-weight:700; padding:3px 10px; border-radius:99px;">
                    {{ $low->count() }} item
                </span>
            @endif
        </div>

        <ul style="padding:0; margin:0; display:flex; flex-direction:column; gap:8px;">
            @forelse ($low as $p)
                <li style="list-style:none; padding:12px 14px; background:#fff5f5; border:1px solid #fed7d7; border-radius:10px; display:flex; justify-content:space-between; align-items:center; transition:background 0.2s;">
                    <a href="{{ route('admin.products.show', $p) }}" style="font-size:13px; font-weight:600; color:#0f172a; text-decoration:none;">
                        {{ $p->nama }}
                    </a>
                    <span style="background:#dc2626; color:#fff; font-size:12px; font-weight:700; padding:2px 8px; border-radius:6px; flex-shrink:0;">
                        {{ $p->stok }} unit
                    </span>
                </li>
            @empty
                <li style="list-style:none; text-align:center; padding:28px 16px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; color:#166534; font-size:13px; font-weight:500;">
                    <div style="font-size:24px; margin-bottom:6px;">✅</div>
                    Semua stok produk dalam batas aman.
                </li>
            @endforelse
        </ul>
        
        <div style="margin-top:16px; text-align:center;">
            <a href="{{ route('admin.products') }}" class="btn sm line" style="width:100%; justify-content:center; font-size:13px;">
                Lihat Semua Katalog Produk
            </a>
        </div>
    </div>
</div>

<style>
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(11,34,57,0.08) !important;
    }
</style>
@endsection
