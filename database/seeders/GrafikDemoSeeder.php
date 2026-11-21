<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class GrafikDemoSeeder extends Seeder
{
    public function run(): void
    {
        $klien = User::where('role', 'klien')->get();
        $produk = Product::all();

        if ($klien->isEmpty() || $produk->isEmpty()) {
            $this->command?->warn('Butuh minimal 1 klien dan 1 produk. Jalankan UserSeeder dan ProductSeeder dulu.');

            return;
        }

        $baru = [
            OrderStatus::Menunggu, OrderStatus::Diproses,
            OrderStatus::Dikirim, OrderStatus::Dikirim, OrderStatus::Ditolak,
        ];
        $lama = [OrderStatus::Dikirim, OrderStatus::Dikirim, OrderStatus::Dikirim, OrderStatus::Ditolak];

        for ($i = 0; $i < 60; $i++) {
            $u = $klien->random();
            $p = $produk->random();
            $jumlah = random_int(1, 5);
            $tanggal = now()->subDays(random_int(0, 330))->setTime(random_int(8, 17), random_int(0, 59));

            // pesanan lebih dari 30 hari lalu sudah selesai (dikirim atau ditolak)
            $pool = $tanggal->lt(now()->subDays(30)) ? $lama : $baru;

            $order = new Order([
                'user_id'     => $u->id,
                'product_id'  => $p->id,
                'produk_nama' => $p->nama,
                'jumlah'      => $jumlah,
                'alamat'      => 'Jl. Contoh No. ' . random_int(1, 99) . ', Sidoarjo',
                'total'       => $p->harga * $jumlah,
                'status'      => $pool[array_rand($pool)],
            ]);

            // diisi langsung (bukan lewat $fillable) supaya tanggalnya tidak ditimpa
            $order->created_at = $tanggal;
            $order->updated_at = $tanggal;
            $order->save();
        }
    }
}
