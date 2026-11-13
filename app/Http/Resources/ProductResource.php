<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'nama'        => $this->nama,
            'kategori'    => $this->kategori,
            'spesifikasi' => $this->spesifikasi ?? '',
            'harga'       => $this->harga,
            'stok'        => $this->stok,
            'foto_url'    => $this->foto_url,
            'terjual' => (int) ($this->terjual ?? 0),
        ];
    }
}
