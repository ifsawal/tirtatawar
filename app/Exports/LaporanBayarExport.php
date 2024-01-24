<?php

namespace App\Exports;

use App\Http\Controllers\Api\Laporan\LaporanPetugasController;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanBayarExport implements FromCollection
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
}
