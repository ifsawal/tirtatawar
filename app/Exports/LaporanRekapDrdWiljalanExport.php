<?php

namespace App\Exports;

use App\Models\Master\Golongan;
use App\Models\Master\Wiljalan;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Api\Laporan\LaporanRekapDrdWiljalanController;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class LaporanRekapDrdWiljalanExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $r;
    protected $wiljalan;
    protected $gol;
    protected $judul = [];

    function __construct($r)
    {
        $this->r = $r;
        $this->wiljalan = Wiljalan::all();
        $this->gol = Golongan::all(['id', 'golongan']);

        $judul1 = [];
        $judul1 = ["No", "Nama Wilayah/Jalan"];
        foreach ($this->gol as $g) {
            array_push($judul1, $g['golongan']);
            array_push($judul1, "");
            array_push($judul1, "");
        }

        $judul2 = [];
        $judul2 = ['', ''];
        foreach ($this->gol as $g) {
            array_push($judul2, "Pelanggan");
            array_push($judul2, "M3");
            array_push($judul2, "Total");
        }

        $this->judul = [
            $judul1, $judul2
        ];
    }

    public function collection()
    {
        return LaporanRekapDrdWiljalanController::export_drd_wilayah($this->r, $this->wiljalan, $this->gol);
    }


    public function headings(): array
    {
        return $this->judul;
    }

    //     public function styles(Worksheet $sheet)
    // {
    //     return [
    //        // Style the first row as bold text.
    //        1    => ['font' => ['bold' => true]],
    //     ];
    // }
}
