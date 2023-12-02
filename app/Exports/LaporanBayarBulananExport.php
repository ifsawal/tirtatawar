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
            "Nama Jalan",
            "Bulan",
            "Tahun",
            "Tagihan Pemakaian",
            "Adm",
            "Pajak",
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
            'C' => 23,
            'D' => 8,
            'E' => 8,
            'F' => 14,
            'G' => 10,
            'H' => 10,
            'I' => 12,
            'J' => 7,
            'K' => 15,
            'L' => 15,
        ];
    }
}
