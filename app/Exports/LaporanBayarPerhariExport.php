<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use App\Http\Controllers\Api\Laporan\LaporanBayarController;


class LaporanBayarPerhariExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $user;
    public $tanggal;
    public $r;

    function __construct($r)
    {
        $this->r = $r;
    }
    public function collection()
    {
        return LaporanBayarController::proses_download_laporan_bayar_excel($this->r);
    }
}
