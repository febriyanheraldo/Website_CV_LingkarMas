<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleSoftwareIO\QrCode\Generator as QrCode;

class GenerateAppQrCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:generate
                            {--url= : URL yang akan di-encode dalam QR Code (default: URL download APK)}
                            {--size=300 : Ukuran QR Code dalam pixel (default: 300)}
                            {--format=svg : Format output: svg (default, tanpa ekstensi tambahan) atau png (butuh Imagick)}
                            {--filename=qr-aplikasi : Nama file output tanpa ekstensi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate QR Code untuk download aplikasi mobile dan simpan ke public/images/barcode/';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $url      = $this->option('url') ?? 'https://drive.google.com/file/d/1NAsq1zjSW4v1yW9yHAkQyUMQ51QyUR95/view?usp=sharing';
        $size     = (int) $this->option('size');
        $format   = strtolower($this->option('format'));   // 'svg' atau 'png'
        $filename = $this->option('filename');

        // Hanya svg didukung tanpa Imagick; png butuh ext-imagick
        if (! in_array($format, ['svg', 'png'])) {
            $this->error("Format tidak valid. Gunakan 'svg' atau 'png'.");
            return self::FAILURE;
        }

        // Pastikan direktori tersedia
        $dir = public_path('images/barcode');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
            $this->line("  Direktori dibuat: {$dir}");
        }

        $outputPath = "{$dir}/{$filename}.{$format}";

        $this->info("Generating QR Code ({$format})...");
        $this->line("  URL     : {$url}");
        $this->line("  Ukuran  : {$size}×{$size} px");
        $this->line("  Output  : {$outputPath}");

        // Generate dan simpan
        (new QrCode)
            ->format($format)
            ->size($size)
            ->errorCorrection('H')   // High error correction – 30% restore
            ->margin(1)
            ->generate($url, $outputPath);

        if (file_exists($outputPath)) {
            $this->newLine();
            $this->info("✅  QR Code berhasil dibuat!");
            $this->line("  File    : {$outputPath}");
            $this->line("  Akses   : " . url("images/barcode/{$filename}.{$format}"));
            $this->newLine();
            $this->comment("Jika menggunakan SVG di Blade, ganti src di home.blade.php:");
            $this->line("  asset('images/barcode/{$filename}.svg')");
        } else {
            $this->error("❌  Gagal membuat QR Code.");
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
