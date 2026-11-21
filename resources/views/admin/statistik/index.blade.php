@extends('layouts.admin')
@section('title', 'Statistik')

@section('content')
@include('partials.back', ['url' => route('admin.dashboard'), 'label' => 'Kembali ke dashboard'])

<div class="head-row">
    <div>
        <h1>Statistik</h1>
        <p class="mu">Pesanan, stok, dan klien dalam bentuk grafik.</p>
    </div>
    <div class="row">
        @foreach ([3 => '3 Bulan', 6 => '6 Bulan', 12 => '12 Bulan'] as $nilai => $label)
            <a class="btn sm {{ $jumlahBulan === $nilai ? '' : 'line' }}"
               href="{{ route('admin.statistik', ['bulan' => $nilai]) }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="chart-grid">
    <div class="panel chart-panel">
        <h3>Tren Pesanan</h3>
        <p class="sub">{{ $jumlahBulan }} bulan terakhir</p>
        <div class="chart-box"><canvas id="chartTrenPesanan"></canvas></div>
    </div>
    <div class="panel chart-panel">
        <h3>Status Pesanan</h3>
        <p class="sub">Komposisi seluruh pesanan</p>
        <div class="chart-box"><canvas id="chartStatusPesanan"></canvas></div>
    </div>
</div>

<div class="chart-grid eq">
    <div class="panel chart-panel">
        <h3>Stok per Kategori</h3>
        <p class="sub">Total unit tersedia</p>
        <div class="chart-box"><canvas id="chartStokKategori"></canvas></div>
    </div>
    <div class="panel chart-panel">
        <h3>Ketersediaan Stok</h3>
        <p class="sub">Jumlah produk per kondisi stok</p>
        <div class="chart-box"><canvas id="chartKetersediaan"></canvas></div>
    </div>
</div>

<div class="chart-grid eq">
    <div class="panel chart-panel">
        <h3>Produk Terlaris</h3>
        <p class="sub">5 teratas berdasarkan unit dipesan</p>
        <div class="chart-box"><canvas id="chartProdukTerlaris"></canvas></div>
    </div>
    <div class="panel chart-panel">
        <h3>Klien Terbanyak</h3>
        <p class="sub">7 teratas berdasarkan jumlah pesanan</p>
        <div class="chart-box"><canvas id="chartKlienTerbanyak"></canvas></div>
    </div>
</div>
@endsection

@section('scripts')
    @include('partials.chart-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof renderChart === 'undefined') return;

            const tren = @json($trenPesanan, JSON_HEX_TAG | JSON_HEX_APOS);
            const status = @json($statusPesanan, JSON_HEX_TAG | JSON_HEX_APOS);
            const stok = @json($stokKategori, JSON_HEX_TAG | JSON_HEX_APOS);
            const ketersediaan = @json($ketersediaanStok, JSON_HEX_TAG | JSON_HEX_APOS);
            const terlaris = @json($produkTerlaris, JSON_HEX_TAG | JSON_HEX_APOS);
            const klien = @json($klienTerbanyak, JSON_HEX_TAG | JSON_HEX_APOS);

            const horizontal = function (extra) {
                return chartOptions(Object.assign({
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, grid: { display: true, color: palette.grid } }, y: { grid: { display: false } } },
                }, extra || {}));
            };

            // Tren: di Dashboard bertipe line, di sini bar (tipe grafik = pilihan tampilan)
            renderChart('chartTrenPesanan', {
                type: 'bar',
                data: {
                    labels: tren.labels,
                    datasets: [
                        { label: 'Pesanan masuk', data: tren.masuk, backgroundColor: palette.primary, borderRadius: 6, maxBarThickness: 34 },
                        { label: 'Dikirim', data: tren.dikirim, backgroundColor: palette.success, borderRadius: 6, maxBarThickness: 34 },
                    ],
                },
                options: chartOptions(),
            });

            renderChart('chartStatusPesanan', {
                type: 'doughnut',
                data: {
                    labels: status.labels,
                    datasets: [{ data: status.data, backgroundColor: status.colors, borderWidth: 0, hoverOffset: 6 }],
                },
                options: chartOptions({ scales: false, cutout: '62%', plugins: { legend: { position: 'bottom', align: 'center' } } }),
            });

            renderChart('chartStokKategori', {
                type: 'bar',
                data: {
                    labels: stok.labels,
                    datasets: [{ label: 'Total stok (unit)', data: stok.data, backgroundColor: stok.colors, borderRadius: 8, maxBarThickness: 46 }],
                },
                options: chartOptions({
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { afterLabel: function (c) { return stok.item[c.dataIndex] + ' jenis produk'; } } },
                    },
                }),
            });

            renderChart('chartKetersediaan', {
                type: 'polarArea',
                data: {
                    labels: ketersediaan.labels,
                    datasets: [{ data: ketersediaan.data, backgroundColor: ketersediaan.colors, borderWidth: 0 }],
                },
                options: chartOptions({
                    scales: { r: { ticks: { display: false }, grid: { color: palette.grid } } },
                    plugins: { legend: { position: 'bottom', align: 'center' } },
                }),
            });

            renderChart('chartProdukTerlaris', {
                type: 'bar',
                data: {
                    labels: terlaris.labels,
                    datasets: [{ label: 'Unit dipesan', data: terlaris.data, backgroundColor: palette.accent, borderRadius: 6, maxBarThickness: 26 }],
                },
                options: horizontal(),
            });

            renderChart('chartKlienTerbanyak', {
                type: 'bar',
                data: {
                    labels: klien.labels,
                    datasets: [{ label: 'Jumlah pesanan', data: klien.data, backgroundColor: palette.info, borderRadius: 6, maxBarThickness: 26 }],
                },
                options: horizontal({
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { afterLabel: function (c) { return klien.item[c.dataIndex] + ' unit dipesan'; } } },
                    },
                }),
            });
        });
    </script>
@endsection
