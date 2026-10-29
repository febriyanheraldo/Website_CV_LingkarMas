<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static ?array $cache = null;

    public static function get(string $key, ?string $default = null): ?string
    {
        static::$cache ??= static::pluck('value', 'key')->all();

        return static::$cache[$key] ?? $default;
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::$cache = null;
    }

    /** Nomor WhatsApp format internasional (62...), diambil dari telepon perusahaan. */
    public static function waNumber(): string
    {
        $n = preg_replace('/\D/', '', (string) static::get('telepon', ''));

        return str_starts_with($n, '0') ? '62' . substr($n, 1) : $n;
    }
}
