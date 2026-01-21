<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use App\Http\Controllers\Api\Laporan\LaporanBelumBayarController;

class LaporanBelumBayarExport implements FromCollection, WithHeadings, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $tahun,$batas;

    function __construct($tahun, $batas)
    {
        $this->tahun = $tahun;
        $this->batas = $batas;
    }




    public function collection()
    {
        return LaporanBelumBayarController::laporan_belum_bayar($this->tahun,$this->batas);
    }


    public function headings(): array
    {
        return [
            ['Laporan Pelanggan Belum Bayar Tahun '.$this->tahun],
            [''],
            [
            "Nomor Pel",
            "Nama Pelanggan",
            "Golongan",
            "Wil/Jalan",
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9",
            "10",
            "11",
            "12",
            "Total",
        ]];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 30,
            'C' => 25,
            'D' => 30,
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 10,
            'I' => 10,
            'J' => 10,
            'L' => 10,
            'M' => 10,
            'N' => 10,
            'O' => 10,
            'P' => 10,
            'Q' => 15,
        ];
    }
}
