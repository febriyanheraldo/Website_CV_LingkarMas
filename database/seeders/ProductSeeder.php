<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'foto' => 'images/produk/pump.jpg',
                'nama' => 'Industrial Pump',
                'kategori' => 'Pompa & Blower',
                'spesifikasi' => "Tipe: Centrifugal Pump\nDaya: 5.5 kW (7.5 HP)\nKapasitas: 120 m³/jam\nHead: 32 m\nBahan casing: Cast iron\nTegangan: 380 V / 50 Hz",
                'harga' => 8500000,
                'stok' => 12,
            ],
            [
                'foto' => 'images/produk/roots-blower.jpg',
                'nama' => 'Industrial Roots Blower',
                'kategori' => 'Pompa & Blower',
                'spesifikasi' => "Tipe: Three-lobe Roots Blower\nDaya: 7.5 kW\nKapasitas udara: 9.5 m³/menit\nTekanan: 49 kPa\nPendingin: Air cooled\nTegangan: 380 V / 50 Hz",
                'harga' => 15750000,
                'stok' => 6,
            ],
            [
                'foto' => 'images/produk/bearing.jpg',
                'nama' => 'Industrial Bearing',
                'kategori' => 'Komponen Mesin',
                'spesifikasi' => "Tipe: Deep Groove Ball Bearing\nDiameter dalam: 50 mm\nDiameter luar: 90 mm\nLebar: 20 mm\nBahan: Chrome steel\nSegel: 2RS (karet ganda)",
                'harga' => 450000,
                'stok' => 40,
            ],
            [
                'foto' => 'images/produk/oil-seal.jpg',
                'nama' => 'Industrial Oil Seal',
                'kategori' => 'Komponen Mesin',
                'spesifikasi' => "Tipe: TC Double Lip\nUkuran: 50 x 72 x 8 mm\nBahan: NBR (Nitrile Rubber)\nSuhu kerja: -30 °C s/d 100 °C\nTekanan maksimum: 0.05 MPa",
                'harga' => 85000,
                'stok' => 3,   // sengaja ≤ 5 untuk menguji "stok menipis"
            ],
        ];

        foreach ($items as $item) {
            Product::create($item);
        }
    }
}
