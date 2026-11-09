<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function exportPdfProduk(Request $request)
    {
        $q = trim((string) $request->q);
        $kat = $request->kategori;

        // Filter sama dengan halaman /admin/produk, tetapi tanpa pagination
        $products = Product::query()
            ->when($q !== '', fn ($x) => $x->where('nama', 'like', "%{$q}%"))
            ->when($kat, fn ($x) => $x->where('kategori', $kat))
            ->orderBy('kategori')
            ->orderBy('nama')
            ->get();

        $pdf = Pdf::loadView('laporan.pdf.produk', [
            'products' => $products,
            'q'        => $q,
            'kat'      => $kat,
            'logo'     => $this->logoDataUri(),
            'telepon'  => Setting::get('telepon', '-'),
            'email'    => Setting::get('email', '-'),
            'pencetak' => $request->user()->nama,
        ])->setPaper('a4', 'portrait');

        $nama = 'laporan-produk-' . date('Y-m-d') . '.pdf';

        // ?unduh=1 -> file langsung terunduh; tanpa itu -> dibuka di tab browser
        return $request->boolean('unduh') ? $pdf->download($nama) : $pdf->stream($nama);
    }

    /** Logo diubah jadi data URI agar pasti terbaca DomPDF (hanya PNG/JPG). */
    private function logoDataUri(): ?string
    {
        $uploaded = Setting::get('logo');

        $candidates = array_filter([
            $uploaded ? storage_path('app/public/' . $uploaded) : null,
            public_path('images/logo.png'),
        ]);

        foreach ($candidates as $path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            if (is_file($path) && in_array($ext, ['png', 'jpg', 'jpeg'], true)) {
                $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';

                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
            }
        }

        return null;
    }
}
