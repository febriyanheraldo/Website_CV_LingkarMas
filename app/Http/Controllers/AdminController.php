<?php

namespace App\Http\Controllers;

use App\Support\DummyData;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $orders = DummyData::orders();
        $count = fn ($s) => count(array_filter($orders, fn ($o) => $o['status'] === $s));

        return view('admin.dashboard', [
            'stat' => ['Menunggu' => $count('Menunggu'), 'Diproses' => $count('Diproses'), 'Dikirim' => $count('Dikirim'), 'Klien' => count(DummyData::clients())],
            'recent' => array_slice($orders, 0, 4),
            'low' => array_filter(DummyData::products(), fn ($p) => $p['stok'] <= 5),
        ]);
    }

    public function products(Request $request)
    {
        $q = strtolower((string) $request->q);
        $kat = $request->kategori;
        $items = array_filter(DummyData::products(), fn ($p) =>
            ($q === '' || str_contains(strtolower($p['nama']), $q)) && (! $kat || $p['kategori'] === $kat));

        return view('admin.products.index', ['items' => $items, 'q' => $request->q, 'kat' => $kat]);
    }

    public function productCreate()
    {
        return view('admin.products.create');
    }

    public function orders(Request $request)
    {
        $status = $request->status;
        $q = strtolower((string) $request->q);
        $items = array_filter(DummyData::orders(), fn ($o) =>
            (! $status || $o['status'] === $status)
            && ($q === '' || str_contains(strtolower($o['klien']), $q) || str_contains(strtolower(DummyData::kode($o['id'])), $q)));

        return view('admin.orders.index', ['items' => $items, 'status' => $status, 'q' => $request->q]);
    }

    public function orderShow(int $id)
    {
        $order = collect(DummyData::orders())->firstWhere('id', $id);
        abort_if(! $order, 404);

        return view('admin.orders.show', ['o' => $order]);
    }

    public function clients()
    {
        return view('admin.clients.index', ['items' => DummyData::clients()]);
    }
}
