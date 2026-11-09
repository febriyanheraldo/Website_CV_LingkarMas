<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function exportPdfProduk(Request $request)
    {
        $q = trim((string) $request->q);
        $kat = $request->kategori;

        $products = Product::query()
            ->when($q !== '', fn ($x) => $x->where('nama', 'like', "%{$q}%"))
            ->when($kat, fn ($x) => $x->where('kategori', $kat))
            ->orderBy('kategori')
            ->orderBy('nama')
            ->get();

        $ttd = Setting::get('ttd');

        $pdf = Pdf::loadView('laporan.pdf.produk', [
            'products'   => $products,
            'q'          => $q,
            'kat'        => $kat,
            'logo'       => $this->logoDataUri(),
            'telepon'    => Setting::get('telepon', '-'),
            'email'      => Setting::get('email', '-'),
            'pencetak'   => $request->user()->nama,
            'ttdNama'    => Setting::get('ttd_nama'),
            'ttdJabatan' => Setting::get('ttd_jabatan'),
            'ttdGambar'  => $ttd && Storage::disk('local')->exists($ttd)
                ? $this->dataUri(Storage::disk('local')->path($ttd))
                : null,
        ])->setPaper('a4', 'portrait');

        $nama = 'laporan-produk-' . date('Y-m-d') . '.pdf';

        return $request->boolean('unduh') ? $pdf->download($nama) : $pdf->stream($nama);
    }

    private function logoDataUri(): ?string
    {
        $uploaded = Setting::get('logo');

        foreach (array_filter([
            $uploaded ? storage_path('app/public/' . $uploaded) : null,
            public_path('images/logo.png'),
        ]) as $path) {
            if ($uri = $this->dataUri($path)) {
                return $uri;
            }
        }

        return null;
    }

    /** Gambar -> data URI agar pasti terbaca DomPDF (hanya PNG/JPG). */
    private function dataUri(string $path): ?string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (! is_file($path) || ! in_array($ext, ['png', 'jpg', 'jpeg'], true)) {
            return null;
        }

        $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }
}
