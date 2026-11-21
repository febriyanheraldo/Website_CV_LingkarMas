<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class StatistikService
{
    /** Warna batang/irisan: ditentukan di server, JavaScript hanya menerimanya. */
    private const WARNA = ['#0b2239', '#f97316', '#3b82f6', '#16a34a', '#8b5cf6', '#d97706', '#e11d48', '#0891b2'];

    /** Pesanan masuk dan pesanan dikirim per bulan (agregasi per bulan di PHP). */
    public function pesananPerBulan(int $bulan = 6): array
    {
        $awal = now()->startOfMonth()->subMonths($bulan - 1);

        $rows = Order::where('created_at', '>=', $awal)->get(['created_at', 'status']);

        $labels = [];
        $masuk = [];
        $dikirim = [];

        for ($i = 0; $i < $bulan; $i++) {
            $bln = $awal->copy()->addMonths($i);
            $kunci = $bln->format('Y-m');

            $bulanIni = $rows->filter(fn ($o) => $o->created_at->format('Y-m') === $kunci);

            $labels[] = $bln->locale('id')->translatedFormat('M Y');
            $masuk[] = $bulanIni->count();
            $dikirim[] = $bulanIni->filter(fn ($o) => $o->status === OrderStatus::Dikirim)->count();
        }

        return ['labels' => $labels, 'masuk' => $masuk, 'dikirim' => $dikirim];
    }

    /** Komposisi status pesanan (COUNT + groupBy di MySQL). */
    public function statusPesanan(): array
    {
        $data = DB::table('orders')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $status = OrderStatus::cases();   // Menunggu, Diproses, Dikirim, Ditolak

        return [
            'labels' => array_map(fn ($s) => $s->value, $status),
            'data'   => array_map(fn ($s) => (int) $data->get($s->value, 0), $status),
            'colors' => ['#f59e0b', '#3b82f6', '#16a34a', '#dc2626'],
        ];
    }

    /** Total stok (SUM) dan jumlah jenis produk (COUNT) per kategori, dalam satu query. */
    public function stokPerKategori(int $limit = 8): array
    {
        $rows = DB::table('products')
            ->select('kategori', DB::raw('SUM(stok) as total'), DB::raw('COUNT(*) as item'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return [
            'labels' => $rows->pluck('kategori')->values()->all(),
            'data'   => $rows->map(fn ($r) => (int) $r->total)->values()->all(),
            'item'   => $rows->map(fn ($r) => (int) $r->item)->values()->all(),
            'colors' => array_slice(array_merge(self::WARNA, self::WARNA), 0, $rows->count()),
        ];
    }

    /** Jumlah produk berdasarkan ketersediaan stok. */
    public function ketersediaanStok(): array
    {
        $r = DB::table('products')->selectRaw(
            'COALESCE(SUM(CASE WHEN stok > 5 THEN 1 ELSE 0 END), 0) as aman, '
            . 'COALESCE(SUM(CASE WHEN stok BETWEEN 1 AND 5 THEN 1 ELSE 0 END), 0) as menipis, '
            . 'COALESCE(SUM(CASE WHEN stok <= 0 THEN 1 ELSE 0 END), 0) as habis'
        )->first();

        return [
            'labels' => ['Aman (di atas 5)', 'Menipis (1 sampai 5)', 'Habis (pre-order)'],
            'data'   => [(int) $r->aman, (int) $r->menipis, (int) $r->habis],
            'colors' => ['#16a34a', '#f59e0b', '#dc2626'],
        ];
    }

    /** Produk dengan unit terbanyak dipesan (pesanan Ditolak tidak dihitung). */
    public function produkTerlaris(int $limit = 5): array
    {
        $rows = DB::table('orders')
            ->select('produk_nama', DB::raw('SUM(jumlah) as total'))
            ->where('status', '!=', OrderStatus::Ditolak->value)
            ->groupBy('produk_nama')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return [
            'labels' => $rows->pluck('produk_nama')->values()->all(),
            'data'   => $rows->map(fn ($r) => (int) $r->total)->values()->all(),
        ];
    }

    /** Klien dengan pesanan terbanyak: COUNT pesanan + SUM unit. */
    public function klienTerbanyak(int $limit = 7): array
    {
        $rows = DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->select('users.nama', DB::raw('COUNT(*) as total'), DB::raw('SUM(orders.jumlah) as item'))
            ->where('orders.status', '!=', OrderStatus::Ditolak->value)
            ->groupBy('users.id', 'users.nama')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return [
            'labels' => $rows->pluck('nama')->values()->all(),
            'data'   => $rows->map(fn ($r) => (int) $r->total)->values()->all(),
            'item'   => $rows->map(fn ($r) => (int) $r->item)->values()->all(),
        ];
    }
}
