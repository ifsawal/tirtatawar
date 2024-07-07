<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use App\Http\Controllers\Api\Laporan\LaporanBayarBankController;

class LaporanBayarBankExport implements FromCollection, WithHeadings, WithColumnWidths
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
        return LaporanBayarBankController::laporanbayarbankdata($this->r);
    }


    public function headings(): array
    {
        return [
            [
                "DATA PEMBAYARAN BANK PERIODE ". date("m-Y",strtotime($this->r->tanggal))
            ],
            [],
            [
                "Tanggal Bayar",
                "No Pel",
                "Nama",
                "Golongan",
                "Nama Jalan",
                "Bulan",
                "Tahun",
                "Pemakaian",
                "Tagihan Pemakaian",
                "Adm",
                "Diskon",
                "Denda",
                "Pajak",
                "Total Bayar",
                "Status Bayar",
                "Vendor",
                "VA",
                "Bank",
                "Tipe",
                "Jumlah Transfer",
                "No Reff",
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 16,
            'B' => 8,
            'C' => 28,
            'D' => 23,
            'E' => 23,
            'F' => 8,
            'G' => 8,
            'H' => 8,
            'I' => 14,
            'J' => 10,
            'K' => 10,
            'L' => 10,
            'M' => 7,
            'N' => 12,
            'O' => 15,
            'P' => 15,
            'Q' => 15,
            'R' => 15,
            'S' => 15,
            'T' => 15,
            'U' => 15,
            'P' => 15,
        ];
    }
}
