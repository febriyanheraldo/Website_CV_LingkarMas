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
    /** Membuat pesanan (Pre-Order): klien tetap bisa pre-order meskipun stok 0. */
    public function create(User $user, int $productId, int $jumlah, string $alamat): Order
    {
        return DB::transaction(function () use ($user, $productId, $jumlah, $alamat) {
            $product = Product::whereKey($productId)->lockForUpdate()->first();

            if (! $product) {
                throw ValidationException::withMessages(['product_id' => ['Produk tidak ditemukan']]);
            }

            if ($jumlah < 1) {
                throw ValidationException::withMessages(['jumlah' => ['Jumlah pre-order minimal 1 unit']]);
            }

            $alokasi = max(0, min($product->stok, $jumlah));

            $order = Order::create([
                'user_id'           => $user->id,
                'product_id'        => $product->id,
                'produk_nama'       => $product->nama,
                'jumlah'            => $jumlah,
                'stok_dialokasikan' => $alokasi,
                'alamat'            => $alamat,
                'total'             => $product->harga * $jumlah,
                'status'            => OrderStatus::Menunggu,
            ]);

            if ($alokasi > 0) {
                $product->decrement('stok', $alokasi);
            }

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
        if ($order->product_id && $order->stok_dialokasikan > 0) {
            Product::whereKey($order->product_id)->increment('stok', $order->stok_dialokasikan);
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


    /**
     * Perbarui pesanan dari import Excel.
     * Mengembalikan true jika data benar-benar berubah, false jika sama persis (tidak ada yang diubah).
     */
    public function updateFromImport(
        Order $order,
        User $user,
        Product $product,
        int $jumlah,
        string $alamat
    ): bool {
        return DB::transaction(function () use ($order, $user, $product, $jumlah, $alamat) {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            // Pesanan yang sudah dikirim atau ditolak tidak boleh diubah.
            if (! in_array($lockedOrder->status, [
                OrderStatus::Menunggu,
                OrderStatus::Diproses,
            ], true)) {
                throw ValidationException::withMessages([
                    'kode' => 'Pesanan dengan status ' . $lockedOrder->status->value . ' tidak dapat diperbarui.',
                ]);
            }

            if ($jumlah < 1) {
                throw ValidationException::withMessages([
                    'jumlah' => 'Jumlah pesanan minimal 1.',
                ]);
            }

            $alamatBaru = mb_substr($alamat, 0, 500);

            // Cek apakah ada perubahan data — jika sama persis, skip update stok & DB.
            $tidakBerubah = $lockedOrder->user_id    === $user->id
                && $lockedOrder->product_id  === $product->id
                && $lockedOrder->jumlah      === $jumlah
                && $lockedOrder->alamat      === $alamatBaru;

            if ($tidakBerubah) {
                return false; // tidak ada yang diubah
            }

            // Kunci produk lama dan baru agar perubahan stok aman.
            $productIds = array_unique(array_filter([
                $lockedOrder->product_id,
                $product->id,
            ]));

            $products = Product::query()
                ->whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $oldProduct = $lockedOrder->product_id
                ? $products->get($lockedOrder->product_id)
                : null;

            $newProduct = $products->get($product->id);

            if (! $newProduct) {
                throw ValidationException::withMessages([
                    'produk' => 'Produk tidak ditemukan.',
                ]);
            }

            if ($oldProduct) {
                $oldProduct->stok += $lockedOrder->stok_dialokasikan;
                $oldProduct->save();
            }

            $newProduct->refresh();
            $alokasi = min($newProduct->stok, $jumlah);   // sisanya menunggu stok masuk
            $newProduct->stok -= $alokasi;
            $newProduct->save();

            $lockedOrder->update([
                'user_id'     => $user->id,
                'product_id'  => $newProduct->id,
                'produk_nama' => $newProduct->nama,
                'jumlah'      => $jumlah,
                'alamat'      => $alamatBaru,
                'total'       => $newProduct->harga * $jumlah,
                'stok_dialokasikan' => $alokasi,
            ]);

            return true; // benar-benar diperbarui
        });
    }

}
