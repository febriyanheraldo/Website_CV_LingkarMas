<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::put('telepon', '+62 31 8665234');
        Setting::put('email', 'info@lingkarmas.co.id');
    }
}
