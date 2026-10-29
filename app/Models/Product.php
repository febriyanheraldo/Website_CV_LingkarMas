<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = ['nama', 'kategori', 'spesifikasi', 'harga', 'stok', 'foto'];

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

        if (str_starts_with($this->foto, 'images/')) {   // foto bawaan di public/images
            return file_exists(public_path($this->foto)) ? asset($this->foto) : null;
        }

        return Storage::disk('public')->exists($this->foto)
            ? url(Storage::url($this->foto))
            : null;
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
}
