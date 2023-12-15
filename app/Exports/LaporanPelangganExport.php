<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use App\Http\Controllers\Api\Laporan\LaporanPelangganController;

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
            'C' => 15,
            'D' => 8,
            'E' => 8,
            'F' => 10,
            'G' => 25,
            'H' => 25,
            'I' => 25,
            'J' => 15,
            'L' => 15,
        ];
    }
}
