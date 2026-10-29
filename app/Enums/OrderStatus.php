<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Menunggu = 'Menunggu';
    case Diproses = 'Diproses';
    case Dikirim  = 'Dikirim';
    case Ditolak  = 'Ditolak';

    /** Status tujuan yang diizinkan dari status ini. */
    public function nextStatuses(): array
    {
        return match ($this) {
            self::Menunggu => [self::Diproses, self::Ditolak],
            self::Diproses => [self::Dikirim, self::Ditolak],
            default        => [],   // Dikirim & Ditolak = status akhir
        };
    }

    public function canTransitionTo(self $to): bool
    {
        return in_array($to, $this->nextStatuses(), true);
    }

    public function isFinal(): bool
    {
        return $this->nextStatuses() === [];
    }
}
