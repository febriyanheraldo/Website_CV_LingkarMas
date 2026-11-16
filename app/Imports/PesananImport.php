<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PesananImport implements SkipsOnError, ToModel, WithHeadingRow
{
    public int $processed = 0;   // baris berisi data
    public int $imported = 0;    // pesanan berhasil dibuat
    public int $skipped = 0;     // baris dilewati
    public array $log = [];      // alasan tiap baris yang dilewati

    private int $rowNumber = 1;  // baris 1 = judul kolom

    public function __construct(private OrderService $service)
    {
    }

    public function model(array $row)
    {
        $this->rowNumber++;

        $email = strtolower(trim((string) $this->value($row, ['email_klien', 'email'])));
        $produk = trim((string) $this->value($row, ['produk', 'nama_produk']));
        $jumlah = (int) $this->value($row, ['jumlah', 'qty']);
        $alamat = trim((string) $this->value($row, ['alamat']));

        // baris kosong sepenuhnya: diabaikan
        if ($email === '' && $produk === '' && $alamat === '' && $jumlah === 0) {
            return null;
        }

        $this->processed++;

        // normalisasi dan validasi per baris
        if ($email === '') {
            return $this->skip('email klien kosong');
        }
        if ($produk === '') {
            return $this->skip('nama produk kosong');
        }
        if ($jumlah < 1) {
            return $this->skip('jumlah minimal 1');
        }
        if ($alamat === '') {
            return $this->skip('alamat kosong');
        }

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
            // aturan bisnis sama dengan API: stok dikunci, total dari server, status Menunggu
            $this->service->create($user, $product->id, $jumlah, mb_substr($alamat, 0, 500));
            $this->imported++;
        } catch (ValidationException $e) {
            $alasan = collect($e->errors())->flatten()->first() ?? 'data tidak valid';

            return $this->skip("{$alasan} ({$product->nama})");
        }

        return null;   // pesanan sudah disimpan oleh OrderService, tidak ada model untuk disimpan lagi
    }

    // SkipsOnError: error tak terduga pada satu baris tidak menghentikan baris lain
    public function onError(\Throwable $e)
    {
        $this->skipped++;
        $this->log[] = 'Satu baris dilewati: ' . $e->getMessage();
    }

    private function skip(string $alasan)
    {
        $this->skipped++;
        $this->log[] = "Baris {$this->rowNumber}: {$alasan}";

        return null;
    }

    /** Ambil nilai dari beberapa kemungkinan nama kolom. */
    private function value(array $row, array $keys)
    {
        foreach ($keys as $k) {
            if (isset($row[$k]) && $row[$k] !== '') {
                return $row[$k];
            }
        }

        return null;
    }
}
