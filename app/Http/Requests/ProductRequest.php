<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;   // akses sudah dijaga middleware auth + role:admin
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'spesifikasi' => ['nullable', 'string'],
            'harga' => ['required', 'integer', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer' => ':attribute harus berupa angka bulat.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'max' => ':attribute terlalu panjang atau terlalu besar (maks. :max).',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'Nama produk', 'kategori' => 'Kategori', 'spesifikasi' => 'Spesifikasi',
            'harga' => 'Harga', 'stok' => 'Stok', 'foto' => 'Foto',
        ];
    }
}
