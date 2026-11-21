<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\StatistikService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatistikTest extends TestCase
{
    use RefreshDatabase;

    private function produk(string $nama = 'Industrial Pump', string $kategori = 'Pompa & Blower', int $stok = 10): Product
    {
        return Product::create([
            'nama' => $nama, 'kategori' => $kategori,
            'spesifikasi' => 'x', 'harga' => 1000, 'stok' => $stok,
        ]);
    }

    private function pesanan(User $user, Product $p, int $jumlah, OrderStatus $status, $tanggal = null): Order
    {
        $order = new Order([
            'user_id' => $user->id, 'product_id' => $p->id, 'produk_nama' => $p->nama,
            'jumlah' => $jumlah, 'alamat' => 'Jl. A', 'total' => $p->harga * $jumlah, 'status' => $status,
        ]);

        if ($tanggal) {
            $order->created_at = $tanggal;
            $order->updated_at = $tanggal;
        }

        $order->save();

        return $order;
    }

    public function test_tamu_dialihkan_ke_login(): void
    {
        $this->get('/admin/statistik')->assertRedirect(route('login'));
    }

    public function test_klien_ditolak(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin/statistik')->assertForbidden();
    }

    public function test_admin_membuka_statistik_dan_dashboard(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/admin/statistik')->assertOk()->assertViewHas('jumlahBulan', 6);
        $this->get('/admin')->assertOk()
            ->assertViewHasAll(['trenPesanan', 'statusPesanan', 'stokKategori', 'produkTerlaris']);
    }

    public function test_filter_bulan_memakai_whitelist(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/admin/statistik?bulan=12')->assertViewHas('jumlahBulan', 12);
        $this->get('/admin/statistik?bulan=3')->assertViewHas('jumlahBulan', 3);
        $this->get('/admin/statistik?bulan=99')->assertViewHas('jumlahBulan', 6);
        $this->get('/admin/statistik?bulan=abc')->assertViewHas('jumlahBulan', 6);
    }

    public function test_pesanan_per_bulan(): void
    {
        $user = User::factory()->create();
        $p = $this->produk();
        $awalBulan = now()->startOfMonth();

        $this->pesanan($user, $p, 2, OrderStatus::Dikirim, $awalBulan->copy()->addDay());
        $this->pesanan($user, $p, 1, OrderStatus::Menunggu, $awalBulan->copy()->addDays(2));
        $this->pesanan($user, $p, 1, OrderStatus::Dikirim, $awalBulan->copy()->subMonth()->addDay());

        $hasil = app(StatistikService::class)->pesananPerBulan(3);

        $this->assertCount(3, $hasil['labels']);
        $this->assertSame([0, 1, 2], $hasil['masuk']);     // 2 bulan lalu, bulan lalu, bulan ini
        $this->assertSame([0, 1, 1], $hasil['dikirim']);
    }

    public function test_status_pesanan(): void
    {
        $user = User::factory()->create();
        $p = $this->produk();

        $this->pesanan($user, $p, 1, OrderStatus::Menunggu);
        $this->pesanan($user, $p, 1, OrderStatus::Menunggu);
        $this->pesanan($user, $p, 1, OrderStatus::Ditolak);

        $hasil = app(StatistikService::class)->statusPesanan();

        $this->assertSame(['Menunggu', 'Diproses', 'Dikirim', 'Ditolak'], $hasil['labels']);
        $this->assertSame([2, 0, 0, 1], $hasil['data']);
        $this->assertCount(4, $hasil['colors']);
    }

    public function test_produk_terlaris_tidak_menghitung_pesanan_ditolak(): void
    {
        $user = User::factory()->create();
        $a = $this->produk('Produk A');
        $b = $this->produk('Produk B');

        $this->pesanan($user, $a, 5, OrderStatus::Dikirim);
        $this->pesanan($user, $a, 10, OrderStatus::Ditolak);
        $this->pesanan($user, $b, 3, OrderStatus::Menunggu);

        $hasil = app(StatistikService::class)->produkTerlaris(5);

        $this->assertSame(['Produk A', 'Produk B'], $hasil['labels']);
        $this->assertSame([5, 3], $hasil['data']);
    }

    public function test_stok_per_kategori_dan_ketersediaan(): void
    {
        $this->produk('A', 'Pompa & Blower', 10);
        $this->produk('B', 'Pompa & Blower', 3);
        $this->produk('C', 'Komponen Mesin', 0);

        $kategori = app(StatistikService::class)->stokPerKategori(8);
        $this->assertSame(['Pompa & Blower', 'Komponen Mesin'], $kategori['labels']);
        $this->assertSame([13, 0], $kategori['data']);
        $this->assertSame([2, 1], $kategori['item']);

        $stok = app(StatistikService::class)->ketersediaanStok();
        $this->assertSame([1, 1, 1], $stok['data']);   // aman, menipis, habis
    }

    public function test_klien_terbanyak(): void
    {
        $budi = User::factory()->create(['nama' => 'Budi']);
        $siti = User::factory()->create(['nama' => 'Siti']);
        $p = $this->produk();

        $this->pesanan($budi, $p, 2, OrderStatus::Dikirim);
        $this->pesanan($budi, $p, 3, OrderStatus::Menunggu);
        $this->pesanan($siti, $p, 1, OrderStatus::Dikirim);

        $hasil = app(StatistikService::class)->klienTerbanyak(7);

        $this->assertSame(['Budi', 'Siti'], $hasil['labels']);
        $this->assertSame([2, 1], $hasil['data']);
        $this->assertSame([5, 1], $hasil['item']);
    }
}
