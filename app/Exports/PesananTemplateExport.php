<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PesananTemplateExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles
{
    // satu baris contoh dengan email yang tidak terdaftar,
    // jadi kalau lupa dihapus, baris ini hanya dilewati dan tidak membuat pesanan
    public function array(): array
    {
        return [
            // baris contoh, dihapus sebelum import
            ['contoh@klien.test', 'Industrial Pump', 1, 'Jl. Contoh No. 1, Sidoarjo (hapus baris contoh ini sebelum import)'],
        ];
    }

    public function headings(): array
    {
        // urutan sama dengan array()
        return ['Email Klien', 'Produk', 'Jumlah', 'Alamat'];
    }

    public function columnWidths(): array
    {
        // kolom A = email, B = nama produk, C = jumlah, D = alamat
        return ['A' => 30, 'B' => 28, 'C' => 10, 'D' => 65];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // baris pertama = judul kolom
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B2239']],
            ],
        ];
    }
}
