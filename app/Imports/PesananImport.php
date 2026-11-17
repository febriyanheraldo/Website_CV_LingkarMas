<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PesananImport implements SkipsOnError, ToModel, WithHeadingRow
{
    public int $processed = 0;
    public int $imported = 0;
    public int $updated = 0;
    public int $skipped = 0;
    public array $log = [];

    private int $rowNumber = 1;

    public function __construct(private OrderService $service)
    {
    }

    public function model(array $row)
    {
        $this->rowNumber++;

        $kode = strtoupper(trim((string) $this->value($row, ['kode_pesanan', 'kode'])));
        $email = strtolower(trim((string) $this->value($row, ['email_klien', 'email'])));
        $produk = trim((string) $this->value($row, ['produk', 'nama_produk']));
        $jumlah = (int) $this->value($row, ['jumlah', 'qty']);
        $alamat = trim((string) $this->value($row, ['alamat']));

        if ($kode === '' && $email === '' && $produk === '' && $alamat === '' && $jumlah === 0) {
            return null;
        }

        $this->processed++;

        if ($email === '') return $this->skip('email klien kosong');
        if ($produk === '') return $this->skip('nama produk kosong');
        if ($jumlah < 1) return $this->skip('jumlah minimal 1');
        if ($alamat === '') return $this->skip('alamat kosong');

        $user = User::where('role', 'klien')
            ->whereRaw('LOWER(email) = ?', [$email])
            ->first();

        if (! $user) {
            return $this->skip("klien dengan email {$email} tidak ditemukan");
        }

        $product = Product::whereRaw('LOWER(nama) = ?', [mb_strtolower($produk)])->first();

        if (! $product) {
            return $this->skip("produk \"{$produk}\" tidak ditemukan");
        }

        try {
            if ($kode === '') {
                $this->service->create(
                    $user,
                    $product->id,
                    $jumlah,
                    mb_substr($alamat, 0, 500)
                );

                $this->imported++;
                return null;
            }

            if (! preg_match('/^LM-0*(\d+)$/i', $kode, $matches)) {
                return $this->skip("format kode pesanan {$kode} tidak valid");
            }

            $order = Order::find((int) $matches[1]);

            if (! $order) {
                return $this->skip("pesanan dengan kode {$kode} tidak ditemukan");
            }

            $this->service->updateFromImport(
                $order,
                $user,
                $product,
                $jumlah,
                mb_substr($alamat, 0, 500)
            );

            $this->updated++;

        } catch (ValidationException $e) {
            $alasan = collect($e->errors())->flatten()->first() ?? 'data tidak valid';
            return $this->skip($alasan);
        }

        return null;
    }
    public function onError(\Throwable $e)
    {
        $this->skipped++;
        $this->log[] = "Baris {$this->rowNumber}: " . $e->getMessage();
    }

    private function skip(string $alasan)
    {
        $this->skipped++;
        $this->log[] = "Baris {$this->rowNumber}: {$alasan}";

        return null;
    }

    private function value(array $row, array $keys)
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && $row[$key] !== '') {
                return $row[$key];
            }
        }

        return null;
    }
}
