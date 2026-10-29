<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/** Semua logika pesanan dipusatkan di sini (dipakai web dan nanti API). */
class OrderService
{
    public function changeStatus(Order $order, OrderStatus $to): Order
    {
        return DB::transaction(function () use ($order, $to) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if (! $order->status->canTransitionTo($to)) {
                throw ValidationException::withMessages([
                    'status' => "Status {$order->status->value} tidak dapat diubah menjadi {$to->value}.",
                ]);
            }

            // Ditolak: stok dikembalikan sebesar jumlah pesanan
            if ($to === OrderStatus::Ditolak && $order->product_id) {
                Product::whereKey($order->product_id)->increment('stok', $order->jumlah);
            }

            $order->update(['status' => $to]);

            return $order;
        });
    }
}
