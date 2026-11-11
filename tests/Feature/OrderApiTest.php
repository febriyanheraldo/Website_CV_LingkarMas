<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    private function produk(int $stok = 10, int $harga = 100000): Product
    {
        return Product::create([
            'nama' => 'Industrial Pump', 'kategori' => 'Pompa & Blower',
            'spesifikasi' => 'x', 'harga' => $harga, 'stok' => $stok,
        ]);
    }

    private function pesanan(User $user, Product $p, int $jumlah, OrderStatus $status = OrderStatus::Menunggu): Order
    {
        $order = app(OrderService::class)->create($user, $p->id, $jumlah, 'Jl. Contoh 1');
        $order->update(['status' => $status]);

        return $order;
    }

    public function test_stok_tidak_cukup(): void
    {
        $p = $this->produk(2);
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/orders', ['product_id' => $p->id, 'jumlah' => 3, 'alamat' => 'Jl. A'])
            ->assertStatus(422)
            ->assertJson(['message' => 'Stok tidak cukup']);

        $this->assertSame(2, $p->fresh()->stok);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_stok_berkurang_dan_total_dihitung_server(): void
    {
        $p = $this->produk(10, 100000);
        Sanctum::actingAs(User::factory()->create());

        $res = $this->postJson('/api/orders', [
            'product_id' => $p->id, 'jumlah' => 3, 'alamat' => 'Jl. A', 'total' => 1,   // total dari client diabaikan
        ])->assertStatus(201)
          ->assertJsonPath('total', 300000)
          ->assertJsonPath('status', 'Menunggu')
          ->assertJsonPath('kode', 'LM-0001')
          ->assertJsonPath('produk_nama', 'Industrial Pump');

        $this->assertMatchesRegularExpression('/^\d{2} [A-Z][a-z]{2} \d{4}$/', $res->json('tanggal'));
        $this->assertSame(7, $p->fresh()->stok);
    }

    public function test_batal_mengembalikan_stok_dan_menghapus_pesanan(): void
    {
        $p = $this->produk(10);
        $user = User::factory()->create();
        $order = $this->pesanan($user, $p, 3);
        Sanctum::actingAs($user);

        $this->deleteJson("/api/orders/{$order->id}")->assertOk();

        $this->assertSame(10, $p->fresh()->stok);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_batal_ditolak_jika_bukan_menunggu(): void
    {
        $p = $this->produk(10);
        $user = User::factory()->create();
        $order = $this->pesanan($user, $p, 2, OrderStatus::Diproses);
        Sanctum::actingAs($user);

        $this->deleteJson("/api/orders/{$order->id}")->assertForbidden();
        $this->putJson("/api/orders/{$order->id}", ['alamat' => 'Baru'])->assertForbidden();

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    public function test_ubah_alamat_saat_menunggu(): void
    {
        $p = $this->produk(10);
        $user = User::factory()->create();
        $order = $this->pesanan($user, $p, 1);
        Sanctum::actingAs($user);

        $this->putJson("/api/orders/{$order->id}", ['alamat' => 'Jl. Baru 9'])
            ->assertOk()->assertJsonPath('alamat', 'Jl. Baru 9');
    }

    public function test_klien_tidak_bisa_akses_pesanan_orang_lain(): void
    {
        $p = $this->produk(10);
        $pemilik = User::factory()->create();
        $order = $this->pesanan($pemilik, $p, 1);

        Sanctum::actingAs(User::factory()->create());

        $this->putJson("/api/orders/{$order->id}", ['alamat' => 'X'])->assertNotFound();
        $this->deleteJson("/api/orders/{$order->id}")->assertNotFound();
        $this->getJson('/api/orders')->assertOk()->assertJsonCount(0);
    }

    public function test_admin_menolak_pesanan_mengembalikan_stok(): void
    {
        $p = $this->produk(10);
        $order = $this->pesanan(User::factory()->create(), $p, 4);
        $this->assertSame(6, $p->fresh()->stok);

        app(OrderService::class)->changeStatus($order, OrderStatus::Ditolak);

        $this->assertSame(10, $p->fresh()->stok);
        $this->assertSame(OrderStatus::Ditolak, $order->fresh()->status);
    }

    public function test_alur_status_yang_valid(): void
    {
        $order = $this->pesanan(User::factory()->create(), $this->produk(10), 1);
        $service = app(OrderService::class);

        $service->changeStatus($order, OrderStatus::Diproses);
        $service->changeStatus($order, OrderStatus::Dikirim);

        $this->assertSame(OrderStatus::Dikirim, $order->fresh()->status);
    }

    public function test_menunggu_tidak_boleh_langsung_dikirim(): void
    {
        $order = $this->pesanan(User::factory()->create(), $this->produk(10), 1);

        $this->expectException(ValidationException::class);
        app(OrderService::class)->changeStatus($order, OrderStatus::Dikirim);
    }

    public function test_status_akhir_tidak_bisa_diubah(): void
    {
        $order = $this->pesanan(User::factory()->create(), $this->produk(10), 1, OrderStatus::Dikirim);

        $this->expectException(ValidationException::class);
        app(OrderService::class)->changeStatus($order, OrderStatus::Ditolak);
    }
}
