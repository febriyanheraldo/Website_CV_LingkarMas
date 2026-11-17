<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PesananExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnWidths,
    WithStyles,
    WithColumnFormatting
{
    public function __construct(
        // filterPesanan() ada di model Order, untuk status dan pencarian
        private ?string $status = null,
        private string $search = ''
    ) {
    }

    // 1. data mana yang masuk file (eager loading: tidak ada N+1 query)
    public function collection()
    {
        return Order::with('user')
        // filterPesanan() ada di model Order, untuk status dan pencarian
            ->filterPesanan($this->status, $this->search)
            ->latest()
            ->latest('id')
            ->get();
    }

    // 2. judul kolom pada baris pertama (urutan sama dengan map())
    public function headings(): array
    {
        return [
            // urutan sama dengan map()
            'Kode', 'Tanggal', 'Klien', 'Email', 'No. HP',
            'Produk', 'Jumlah', 'Total (Rp)', 'Alamat', 'Status',
        ];
    }

    // 3. satu model menjadi satu baris
    public function map($o): array
    {
        return [
            // urutan sama dengan headings()
            $o->kode,                                   // LM-0001
            $o->created_at->format('d/m/Y'),
            $o->user?->nama ?? 'Akun dihapus',          // pemilik bisa null
            $o->user?->email ?? '-',
            $o->user?->hp ?? '-',
            $o->produk_nama,
            $o->jumlah,                                 // tetap angka
            $o->total,                                  // tetap angka, bisa di-SUM
            $o->alamat,
            $o->status->value,                          // enum -> teks
        ];
    }

    // lebar kolom (kunci = huruf kolom)
    public function columnWidths(): array
    {
        return [
            // kolom A sampai J, total 10 kolom
            'A' => 12, 'B' => 12, 'C' => 22, 'D' => 28, 'E' => 16,
            'F' => 26, 'G' => 10, 'H' => 16, 'I' => 45, 'J' => 12,
        ];
    }

    // format angka pada kolom Total (nilai tetap angka)
    public function columnFormats(): array
    {
        return ['H' => '#,##0'];
    }

    // judul ditebalkan, diberi warna, dan baris pertama dibekukan
    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        return [
            // baris pertama (judul kolom) diberi warna dan teks putih
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B2239']],
            ],
        ];
    }
}
