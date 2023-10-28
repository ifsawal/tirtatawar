<?php

namespace App\Exports;

use App\Http\Controllers\Api\Laporan\LaporanBulananController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanBayarBulananExport implements FromCollection, WithHeadings, WithColumnWidths
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
        return LaporanBulananController::filter($this->r, 'cetak');
    }


    public function headings(): array
    {
        return [
            "Nama",
            "Golongan",
            "Bulan",
            "Tahun",
            "Total Bayar",
            "Status Bayar",
            "Sistem Bayar",
            "Tanggal Bayar",
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 23,
            'C' => 8,
            'D' => 8,
            'E' => 10,
            'F' => 7,
            'G' => 15,
            'H' => 15,
        ];
    }
}
