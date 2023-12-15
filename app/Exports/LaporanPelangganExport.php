<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use App\Http\Controllers\Api\Laporan\LaporanPelangganController;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanPelangganExport implements FromCollection, WithHeadings, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $r;

    function __construct($r)
    {
        $this->r = $r;
    }

    public function collection()
    {
        return LaporanPelangganController::datapangkalan($this->r, 'cetak');
    }

    // public function columnFormats(): array
    // {
    //     return [
    //         'C' => NumberFormat::FORMAT_GENERAL,
    //     ];
    // }

    public function headings(): array
    {
        return [
            "No Pel",
            "Nama Pelanggan",
            "NIK",
            "Lat",
            "Long",
            "Nomor Lama",
            "Golongan",
            "Wilayah Jalan",
            "Rute Air",
            "Desa",
            "Kecamatan",
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 28,
            'C' => 12,
            'D' => 6,
            'E' => 6,
            'F' => 10,
            'G' => 25,
            'H' => 15,
            'I' => 20,
            'J' => 15,
            'L' => 15,
        ];
    }
}
