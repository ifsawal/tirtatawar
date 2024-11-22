<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use App\Http\Controllers\Api\Laporan\LaporanBayarController;

class LaporanBayarPerhariExport implements FromCollection, WithHeadings
// , WithColumnWidths
{


    protected $r;

    function __construct($r)
    {
        $this->r = $r;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return LaporanBayarController::proses_download_laporan_bayar_excel($this->r);
        // return LaporanRekapBulananController::rekap($this->r, 'cetak');
    }


    public function headings(): array
    {
        return [
            "No",
            "Nopel",
            "Nama",
            "Bulan",
            "Jumlah",
            "Golongan",
            "Jalan",
            "Penagih",
            "Tanggal Bayar",

        ];
    }
}
