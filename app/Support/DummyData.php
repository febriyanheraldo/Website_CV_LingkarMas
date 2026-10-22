<?php

namespace App\Support;

/** Data contoh untuk tahap frontend. Nanti diganti query Eloquent. */
class DummyData
{
    public static function products(): array
    {
        return [
            ['id' => 1, 'nama' => 'Industrial Pump', 'kategori' => 'Pompa & Blower', 'harga' => 8500000, 'stok' => 10, 'ikon' => '💧', 'foto' => 'images/produk/pump.jpg', 'ringkas' => 'Centrifugal, 5,5 kW, 120 m³/jam'],
            ['id' => 2, 'nama' => 'Industrial Roots Blower', 'kategori' => 'Pompa & Blower', 'harga' => 15750000, 'stok' => 5, 'ikon' => '🌀', 'foto' => 'images/produk/roots-blower.jpg', 'ringkas' => 'Three-lobe, 7,5 kW, 49 kPa'],
            ['id' => 3, 'nama' => 'Industrial Bearing', 'kategori' => 'Komponen Mesin', 'harga' => 450000, 'stok' => 30, 'ikon' => '⚙️', 'foto' => 'images/produk/bearing.jpg', 'ringkas' => 'Deep groove, 50 × 90 × 20 mm'],
            ['id' => 4, 'nama' => 'Industrial Oil Seal', 'kategori' => 'Komponen Mesin', 'harga' => 85000, 'stok' => 3, 'ikon' => '⭕', 'foto' => 'images/produk/oil-seal.jpg', 'ringkas' => 'TC double lip, 50 × 72 × 8 mm'],
        ];
    }

    public static function orders(): array
    {
        return [
            ['id' => 5, 'klien' => 'Siti Rahmawati', 'produk' => 'Industrial Pump', 'jumlah' => 1, 'total' => 8500000, 'status' => 'Menunggu', 'tanggal' => '20 Okt 2026', 'alamat' => 'Jl. Ahmad Yani No. 45, Surabaya'],
            ['id' => 4, 'klien' => 'Siti Rahmawati', 'produk' => 'Industrial Oil Seal', 'jumlah' => 5, 'total' => 425000, 'status' => 'Ditolak', 'tanggal' => '19 Okt 2026', 'alamat' => 'Jl. Ahmad Yani No. 45, Surabaya'],
            ['id' => 3, 'klien' => 'Siti Rahmawati', 'produk' => 'Industrial Roots Blower', 'jumlah' => 1, 'total' => 15750000, 'status' => 'Dikirim', 'tanggal' => '18 Okt 2026', 'alamat' => 'Jl. Ahmad Yani No. 45, Surabaya'],
            ['id' => 2, 'klien' => 'Budi Santoso', 'produk' => 'Industrial Bearing', 'jumlah' => 10, 'total' => 4500000, 'status' => 'Diproses', 'tanggal' => '17 Okt 2026', 'alamat' => 'Jl. Raya Gedangan No. 12, Sidoarjo'],
            ['id' => 1, 'klien' => 'Budi Santoso', 'produk' => 'Industrial Pump', 'jumlah' => 2, 'total' => 17000000, 'status' => 'Menunggu', 'tanggal' => '16 Okt 2026', 'alamat' => 'Jl. Raya Gedangan No. 12, Sidoarjo'],
        ];
    }

    public static function clients(): array
    {
        return [
            ['nama' => 'Budi Santoso', 'email' => 'budi@example.com', 'hp' => '081311112222', 'pesanan' => 2],
            ['nama' => 'Siti Rahmawati', 'email' => 'siti@example.com', 'hp' => '081322223333', 'pesanan' => 3],
        ];
    }

    public static function kode(int $id): string
    {
        return 'LM-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT);
    }

    public static function rp(int $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.');
    }
}
