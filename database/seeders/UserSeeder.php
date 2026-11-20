<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Data seeder hanya untuk akun contoh Klien (Customer):
        User::firstOrCreate(
            ['email' => 'budi@example.com'],
            [
                'nama' => 'Budi Santoso',
                'hp' => '081311112222',
                'password' => Hash::make('klien123'),
                'role' => 'klien',
            ]
        );

        User::firstOrCreate(
            ['email' => 'siti@example.com'],
            [
                'nama' => 'Siti Rahmawati',
                'hp' => '081322223333',
                'password' => Hash::make('klien123'),
                'role' => 'klien',
            ]
        );
    }
}
