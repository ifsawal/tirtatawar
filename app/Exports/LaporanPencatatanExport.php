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
            'A' => 28,
            'B' => 8,
            'C' => 8,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 5,
            'H' => 15,
            'I' => 25,
            'J' => 25,
            'K' => 30,
        ];
    }
}
