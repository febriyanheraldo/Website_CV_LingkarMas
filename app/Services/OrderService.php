<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    /** Membuat pesanan: validasi stok, kurangi stok, total dihitung server. */
    public function create(User $user, int $productId, int $jumlah, string $alamat): Order
    {
        return DB::transaction(function () use ($user, $productId, $jumlah, $alamat) {
            $product = Product::whereKey($productId)->lockForUpdate()->first();

            if (! $product) {
                throw ValidationException::withMessages(['product_id' => ['Produk tidak ditemukan']]);
            }

            if ($jumlah < 1 || $jumlah > $product->stok) {
                throw ValidationException::withMessages(['jumlah' => ['Stok tidak cukup']]);
            }

            $order = Order::create([
                'user_id'     => $user->id,
                'product_id'  => $product->id,
                'produk_nama' => $product->nama,
                'jumlah'      => $jumlah,
                'alamat'      => $alamat,
                'total'       => $product->harga * $jumlah,   // jangan percaya total dari client
                'status'      => OrderStatus::Menunggu,
            ]);

            $product->decrement('stok', $jumlah);

            return $order;
        });
    }

    /** Klien mengubah alamat (hanya saat Menunggu). */
    public function updateAddress(Order $order, string $alamat): Order
    {
        return DB::transaction(function () use ($order, $alamat) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $this->pastikanMenunggu($order);

            $order->update(['alamat' => $alamat]);

            return $order;
        });
    }

    /** Klien membatalkan: pesanan dihapus, stok dikembalikan (hanya saat Menunggu). */
    public function cancel(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $this->pastikanMenunggu($order);

            $this->kembalikanStok($order);
            $order->delete();
        });
    }

    /** Admin mengubah status: ikuti alur, Ditolak mengembalikan stok. */
    public function changeStatus(Order $order, OrderStatus $to): Order
    {
        return DB::transaction(function () use ($order, $to) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

            if (! $order->status->canTransitionTo($to)) {
                throw ValidationException::withMessages([
                    'status' => "Status {$order->status->value} tidak dapat diubah menjadi {$to->value}",
                ]);
            }

            if ($to === OrderStatus::Ditolak) {
                $this->kembalikanStok($order);
            }

            $order->update(['status' => $to]);

            return $order;
        });
    }

    /** Hapus akun klien: pesanan Menunggu dihapus (stok kembali), lalu akun dihapus. */
    public function deleteAccount(User $user): void
    {
        DB::transaction(function () use ($user) {
            $menunggu = Order::where('user_id', $user->id)
                ->where('status', OrderStatus::Menunggu->value)
                ->lockForUpdate()
                ->get();

            foreach ($menunggu as $order) {
                $this->kembalikanStok($order);
                $order->delete();
            }

            $user->tokens()->delete();
            $user->delete();   // pesanan status lain tetap ada sebagai riwayat (user_id menjadi NULL)
        });
    }

    private function kembalikanStok(Order $order): void
    {
        if ($order->product_id) {
            Product::whereKey($order->product_id)->increment('stok', $order->jumlah);
        }
    }

    private function pastikanMenunggu(Order $order): void
    {
        if ($order->status !== OrderStatus::Menunggu) {
            throw ValidationException::withMessages([
                'status' => ['Pesanan hanya dapat diubah atau dibatalkan saat berstatus Menunggu'],
            ]);
        }
    }
}
