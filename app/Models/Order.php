<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'produk_nama',
        'jumlah', 'alamat', 'total', 'status',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'total'  => 'integer',
        'status' => OrderStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** LM-0001, LM-0002, ... (tidak disimpan di database). */
    public function getKodeAttribute(): string
    {
        return 'LM-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getTanggalAttribute(): string
    {
        return $this->created_at->locale('id')->translatedFormat('d M Y');   // 18 Okt 2026
    }

    public function getTotalRpAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
}
