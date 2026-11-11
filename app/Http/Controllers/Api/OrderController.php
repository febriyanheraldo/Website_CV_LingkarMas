<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->latest()->latest('id')->get();

        return OrderResource::collection($orders);
    }

    public function store(Request $request, OrderService $service)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'jumlah'     => 'required|integer|min:1',
            'alamat'     => 'required|string|max:500',
        ], [
            'product_id.required' => 'Produk wajib dipilih',
            'product_id.exists'   => 'Produk tidak ditemukan',
            'jumlah.required'     => 'Jumlah wajib diisi',
            'jumlah.integer'      => 'Jumlah harus berupa angka',
            'jumlah.min'          => 'Jumlah minimal 1',
            'alamat.required'     => 'Alamat wajib diisi',
        ]);

        $order = $service->create($request->user(), $data['product_id'], $data['jumlah'], $data['alamat']);

        return (new OrderResource($order))->response()->setStatusCode(201);
    }

    public function update(Request $request, int $id, OrderService $service)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('modify', $order);   // 404 milik orang lain, 403 jika bukan Menunggu

        $data = $request->validate(['alamat' => 'required|string|max:500'], [
            'alamat.required' => 'Alamat wajib diisi',
        ]);

        return new OrderResource($service->updateAddress($order, $data['alamat']));
    }

    public function destroy(int $id, OrderService $service)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('modify', $order);

        $service->cancel($order);

        return response()->json(['message' => 'Pesanan dibatalkan']);
    }
}
