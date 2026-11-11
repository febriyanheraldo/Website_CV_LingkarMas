<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->id,
            'nama'  => $this->nama,
            'email' => $this->email,
            'hp'    => $this->hp,
            'role'  => $this->role,
        ];
    }
}
