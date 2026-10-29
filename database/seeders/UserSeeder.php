<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nama' => 'Admin Lingkar Mas',
            'email' => 'admin@lingkarmas.com',
            'hp' => '081200000000',
            'password' => Hash::make('Admin#12345'),
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'hp' => '081311112222',
            'password' => Hash::make('klien123'),
            'role' => 'klien',
        ]);

        User::create([
            'nama' => 'Siti Rahmawati',
            'email' => 'siti@example.com',
            'hp' => '081322223333',
            'password' => Hash::make('klien123'),
            'role' => 'klien',
        ]);
    }
}
