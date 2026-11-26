<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Generator as QrCodeGenerator;

class Product extends Model
{
    protected $fillable = ['nama', 'kategori', 'spesifikasi', 'harga', 'stok', 'foto', 'video'];

    protected $casts = ['harga' => 'integer', 'stok' => 'integer'];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /** URL absolut foto (dipakai web & API). Null jika tidak ada atau file hilang. */
    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        // Jika foto hasil upload (diawali 'storage/'), cek lewat Storage disk public
        if (str_starts_with($this->foto, 'storage/')) {
            $relativePath = Str::after($this->foto, 'storage/');
            return Storage::disk('public')->exists($relativePath) ? asset($this->foto) : null;
        }

        // Jika foto bawaan (diawali 'images/')
        return asset($this->foto);
    }

    public function getVideoUrlAttribute(): ?string
    {
        if (! $this->video || ! file_exists(public_path($this->video))) {
            return null;
        }

        return asset($this->video);
    }

    public function getIkonAttribute(): string
    {
        return str_contains(strtolower($this->kategori), 'pompa') ? '💧' : '⚙️';
    }

    public function getHargaRpAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getRingkasAttribute(): string
    {
        return Str::limit(str_replace("\n", ' · ', (string) $this->spesifikasi), 70);
    }

    // ─── Barcode / QR Code ───────────────────────────────────────────────────

    /**
     * Accessor: kode produk dari kolom id.
     * Contoh: id = 3 → "PRD-00003"
     */
    public function getKodeProdukAttribute(): string
    {
        return 'PRD-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate Barcode HTML (Code 39) menggunakan milon/barcode DNS1D.
     * Sesuai panduan PPT PBL: output berupa elemen HTML murni tanpa perlu simpan file gambar / GD.
     *
     * @param  int  $width   Ketebalan garis (default 2 agar pas di layout panel)
     * @param  int  $height  Tinggi barcode dalam pixel (default 70)
     * @return string        HTML div inline
     */
    public function barcodeHtml(int $width = 2, int $height = 70): string
    {
        return \DNS1D::getBarcodeHTML($this->kode_produk, 'C39', $width, $height);
    }

    /**
     * Generate QR Code (SVG inline) dari kode_produk.
     *
     * @param  int  $size    Ukuran dalam pixel (default 200)
     * @return string        SVG markup
     */
    public function qrCodeSvg(int $size = 200): string
    {
        return (new QrCodeGenerator)
            ->size($size)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($this->kode_produk);
    }
}
