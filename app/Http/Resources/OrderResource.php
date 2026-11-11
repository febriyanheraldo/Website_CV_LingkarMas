<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'kode'        => $this->kode,
            'tanggal'     => $this->tanggal,        
            'status'      => $this->status->value,
            'product_id'  => $this->product_id,
            'produk_nama' => $this->produk_nama,
            'jumlah'      => $this->jumlah,
            'alamat'      => $this->alamat,
            'total'       => $this->total,
        ];
    }
}
