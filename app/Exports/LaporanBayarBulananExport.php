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
            "No Pel",
            "Nama",
            "Golongan",
            "Nama Jalan",
            "Bulan",
            "Tahun",
            "Pemakaian",
            "Tagihan Pemakaian",
            "Adm",
            "Pajak",
            "Total Bayar",
            "Status Bayar",
            "Sistem Bayar",
            "Tanggal Bayar",
            "Penagih",
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 28,
            'C' => 23,
            'D' => 23,
            'E' => 8,
            'F' => 8,
            'g' => 8,
            'H' => 14,
            'I' => 10,
            'J' => 10,
            'K' => 12,
            'L' => 7,
            'M' => 15,
            'N' => 15,
            'N' => 23,
        ];
    }
}
