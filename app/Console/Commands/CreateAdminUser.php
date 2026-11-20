<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin
                            {--nama= : Nama lengkap admin}
                            {--email= : Alamat email admin}
                            {--password= : Password akun admin}
                            {--hp= : Nomor handphone/telepon admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Buat akun Administrator baru secara aman langsung dari terminal (tanpa ditulis di seeder)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('===============================================');
        $this->info('   PEMBUATAN AKUN ADMINISTRATOR CV. LINGKAR MAS');
        $this->info('===============================================');

        // 1. Ambil Nama
        $nama = $this->option('nama') ?: $this->ask('Masukkan Nama Lengkap Admin', 'Admin Lingkar Mas');

        // 2. Ambil Email
        $email = $this->option('email');
        while (! $email) {
            $inputEmail = $this->ask('Masukkan Alamat Email Admin');
            $validator = Validator::make(['email' => $inputEmail], [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $this->error('Format email tidak valid. Silakan coba lagi.');
            } else {
                $email = $inputEmail;
            }
        }

        // Cek apakah email sudah terdaftar
        $existing = User::where('email', $email)->first();
        if ($existing) {
            if (! $this->confirm("Akun dengan email [{$email}] sudah ada. Apakah Anda ingin memperbarui password & menjadikannya Admin?")) {
                $this->warn('Operasi dibatalkan.');
                return self::SUCCESS;
            }
        }

        // 3. Ambil Password
        $password = $this->option('password');
        while (! $password) {
            $inputPassword = $this->secret('Masukkan Password Admin (karakter disembunyikan)');
            if (strlen((string) $inputPassword) < 6) {
                $this->error('Password minimal 6 karakter. Silakan coba lagi.');
            } else {
                $confirmPassword = $this->secret('Ketik Ulang Password untuk Konfirmasi');
                if ($inputPassword !== $confirmPassword) {
                    $this->error('Konfirmasi password tidak cocok! Silakan ulangi.');
                } else {
                    $password = $inputPassword;
                }
            }
        }

        // 4. Ambil Nomor HP
        $hp = $this->option('hp') ?: $this->ask('Masukkan Nomor HP/WhatsApp Admin (opsional)', '081200000000');

        // Simpan / Perbarui User
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'nama'     => $nama,
                'hp'       => $hp,
                'password' => $password, // di-hash otomatis oleh model casts
                'role'     => 'admin',
            ]
        );

        $this->newLine();
        $this->info("✅ Berhasil! Akun Admin [{$user->email}] telah siap digunakan.");
        $this->line("   Role : {$user->role}");
        $this->line("   Login: " . url('/login'));
        $this->newLine();

        return self::SUCCESS;
    }
}
