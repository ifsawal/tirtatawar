<?php

namespace App\Exports;

use App\Http\Controllers\Api\Laporan\LaporanBelumBayarController;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanBelumBayarExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $tahun;

    function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return LaporanBelumBayarController::laporan_belum_bayar($this->tahun);
    }
}
