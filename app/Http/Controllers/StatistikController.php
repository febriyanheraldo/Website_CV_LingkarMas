<?php

namespace App\Http\Controllers;

use App\Services\StatistikService;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    public function index(Request $request, StatistikService $statistik)
    {
        // whitelist: hanya 3, 6, atau 12 yang diterima
        $jumlahBulan = (int) $request->query('bulan', 6);
        $jumlahBulan = in_array($jumlahBulan, [3, 6, 12], true) ? $jumlahBulan : 6;

        return view('admin.statistik.index', [
            'jumlahBulan'      => $jumlahBulan,
            'trenPesanan'      => $statistik->pesananPerBulan($jumlahBulan),
            'statusPesanan'    => $statistik->statusPesanan(),
            'stokKategori'     => $statistik->stokPerKategori(8),
            'ketersediaanStok' => $statistik->ketersediaanStok(),
            'produkTerlaris'   => $statistik->produkTerlaris(5),
            'klienTerbanyak'   => $statistik->klienTerbanyak(7),
        ]);
    }
}
