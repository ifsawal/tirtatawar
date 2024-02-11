<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use App\Http\Controllers\Api\Laporan\LaporanPetugasController;

class LaporanBayarExport implements FromCollection, WithHeadings, WithColumnWidths, WithTitle
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
        return LaporanPetugasController::rekap($this->r, 'cetak');
    }

    public function headings(): array
    {
        $judul_tab1 = ["DAFTAR BAYAR BERDASARKAN PETUGAS"];
        $enter = [""];
        $judul_tab2 = [
            "Nama Petugas",
            "Bulan",
            "Tahun",
            "Jumlah Pelanggan",
            "Pelanggan Terbayar",
            "Pelanggan Blm Bayar",
            "Total Uang Bayar",
            "Uang Terbayar",
            "Uang Blm Terbayar",
        ];

        $judul = [$judul_tab1, $enter, $judul_tab2];
        return $judul;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 6,
            'C' => 10,
            'D' => 17,
            'E' => 17,
            'F' => 17,
            'G' => 20,
            'H' => 20,
            'I' => 20,
        ];
    }

    public function title(): string
    {
        return 'Laporan';
    }
}
