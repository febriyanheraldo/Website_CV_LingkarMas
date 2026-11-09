<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Produk</title>
    <style>
        @page { margin: 28px 32px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #111; }

        table { width: 100%; border-collapse: collapse; }
        .kop td { border: 0; padding: 0; vertical-align: middle; }
        .kop { border-bottom: 2px solid #0b2239; margin-bottom: 14px; }
        .kop img { height: 46px; }
        .kop .nama { font-size: 16px; font-weight: bold; color: #0b2239; }
        .kop .info { font-size: 10px; color: #444; }

        h2 { text-align: center; margin: 4px 0 2px; font-size: 15px; }
        .sub { text-align: center; color: #444; margin-bottom: 12px; }

        .data th, .data td { border: 1px solid #333; padding: 6px 8px; }
        .data th { background: #0b2239; color: #fff; text-align: left; }
        .data tr:nth-child(even) td { background: #f4f6f9; }
        .r { text-align: right; }
        .c { text-align: center; }
        .low { color: #b91c1c; font-weight: bold; }

        .ringkas { width: 50%; margin-top: 14px; }
        .ringkas td { border: 0; padding: 3px 0; }

        .ttd { width: 100%; margin-top: 28px; }
        .ttd td { border: 0; width: 50%; vertical-align: top; }
    </style>
</head>
<body>

    <table class="kop">
        <tr>
            @if ($logo)
                <td style="width:60px"><img src="{{ $logo }}" alt="Logo"></td>
            @endif
            <td>
                <div class="nama">CV. Lingkar Mas</div>
                <div class="info">
                    Jl. Raya Waru No. 123, Waru, Sidoarjo, Jawa Timur 61256<br>
                    Telp: {{ $telepon }} &nbsp;|&nbsp; Email: {{ $email }}
                </div>
            </td>
        </tr>
    </table>

    <h2>Laporan Produk dan Stok</h2>
    <div class="sub">
        Tanggal cetak: {{ now()->format('d/m/Y H:i') }}<br>
        @if ($kat || $q !== '')
            Filter:
            @if ($kat) kategori "{{ $kat }}" @endif
            @if ($q !== '') pencarian "{{ $q }}" @endif
        @else
            Semua produk
        @endif
    </div>

    <table class="data">
        <thead>
            <tr>
                <th class="c" style="width:28px">No</th>
                <th>Nama produk</th>
                <th>Kategori</th>
                <th class="r">Harga</th>
                <th class="c">Stok</th>
                <th class="c">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $p)
                <tr>
                    <td class="c">{{ $loop->iteration }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->kategori }}</td>
                    <td class="r">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                    <td class="c">{{ $p->stok }}</td>
                    <td class="c">
                        @if ($p->stok <= 5) <span class="low">Stok menipis</span> @else Aman @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="c">Tidak ada data produk.</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="ringkas">
        <tr><td>Jumlah produk</td><td>: {{ $products->count() }}</td></tr>
        <tr><td>Total unit stok</td><td>: {{ number_format($products->sum('stok'), 0, ',', '.') }}</td></tr>
        <tr>
            <td>Nilai persediaan</td>
            <td>: Rp {{ number_format($products->sum(fn ($p) => $p->harga * $p->stok), 0, ',', '.') }}</td>
        </tr>
        <tr><td>Produk stok menipis</td><td>: {{ $products->where('stok', '<=', 5)->count() }}</td></tr>
    </table>

    <table class="ttd">
        <tr>
            <td></td>
            <td class="c">
                Sidoarjo, {{ now()->format('d/m/Y') }}<br>
                Dicetak oleh,<br><br><br><br>
                <b>{{ $pencetak }}</b>
            </td>
        </tr>
    </table>

</body>
</html>
