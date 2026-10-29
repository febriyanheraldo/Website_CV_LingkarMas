<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $budi = User::where('email', 'budi@example.com')->first();
        $siti = User::where('email', 'siti@example.com')->first();

        $pump    = Product::where('nama', 'Industrial Pump')->first();
        $blower  = Product::where('nama', 'Industrial Roots Blower')->first();
        $bearing = Product::where('nama', 'Industrial Bearing')->first();
        $seal    = Product::where('nama', 'Industrial Oil Seal')->first();

        $rows = [
            [$budi, $pump,    2,  'Menunggu', 'Jl. Raya Gedangan No. 12, Sidoarjo'],
            [$budi, $bearing, 10, 'Diproses', 'Jl. Raya Gedangan No. 12, Sidoarjo'],
            [$siti, $blower,  1,  'Dikirim',  'Jl. Ahmad Yani No. 45, Surabaya'],
            [$siti, $seal,    5,  'Ditolak',  'Jl. Ahmad Yani No. 45, Surabaya'],
            [$siti, $pump,    1,  'Menunggu', 'Jl. Ahmad Yani No. 45, Surabaya'],
        ];

        foreach ($rows as $i => [$user, $product, $jumlah, $status, $alamat]) {
            Order::create([
                'user_id'     => $user->id,
                'product_id'  => $product->id,
                'produk_nama' => $product->nama,
                'jumlah'      => $jumlah,
                'alamat'      => $alamat,
                'total'       => $product->harga * $jumlah,
                'status'      => $status,
                'created_at'  => now()->subDays(5 - $i),
                'updated_at'  => now()->subDays(5 - $i),
            ]);

            // pesanan Ditolak tidak mengurangi stok
            if ($status !== 'Ditolak') {
                $product->decrement('stok', $jumlah);
            }
        }
    }
}
