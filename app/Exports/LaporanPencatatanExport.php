<?php

namespace App\Exports;

use App\Http\Controllers\Api\Laporan\LaporanPencatatanController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPencatatanExport implements FromCollection, WithHeadings, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $r;
    protected $id;

    function __construct($r, $id)
    {
        $this->r = $r;
        $this->id = $id;
    }

    public function collection()
    {
        return LaporanPencatatanController::filter($this->r, $this->id, 'cetak');
    }


    public function headings(): array
    {
        return [
            "No Pelanggan",
            "Nama",
            "Bulan",
            "Tahun",
            "Catatan Awal",
            "Catatan Akhir",
            "Pemakaian",
            "Sistem Catat",
            "User Pencatat",
            "Golongan",
            "Desa",
            "Wilayah Jalan",
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 28,
            'C' => 8,
            'D' => 8,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 5,
            'I' => 15,
            'J' => 25,
            'K' => 25,
            'L' => 30,
        ];
    }
}
