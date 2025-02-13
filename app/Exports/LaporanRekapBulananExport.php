<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use App\Http\Controllers\Api\Laporan\LaporanRekapBulananController;

class LaporanRekapBulananExport implements FromCollection, WithHeadings, WithColumnWidths
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
        return LaporanRekapBulananController::rekap($this->r, 'cetak');
    }


    public function headings(): array
    {
        return [
            "Nama Jalan",
            "Bulan",
            "Tahun",
            "Jumlah Pelanggan",
            "Pelanggan Tercatat",
            "Pemakaian",
            "Total Tagihan",
            "Adm",
            "Pajak",
            "DRD",
            "Status Update",
            "Terbayar",
            "Sisa",
            "Persentase",
            "Denda",
            "Denda + Terbayar",
            "Pelanggan belum bayar",
        ];
    }
    

    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 8,
            'C' => 8,
            'D' => 15,
            'E' => 8,
            'F' => 8,
            'G' => 15,
            'H' => 10,
            'I' => 8,
            'J' => 15,
            'K' => 8,
            'L' => 12,
            'M' => 12,
            'N' => 12,
            'O' => 12,
            'P' => 12,
            'Q' => 18,

        ];
    }
}
