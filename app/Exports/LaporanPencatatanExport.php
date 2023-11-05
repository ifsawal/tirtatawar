<?php

namespace App\Exports;

use App\Http\Controllers\Api\Laporan\LaporanPencatatanController;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanPencatatanExport implements FromCollection
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
}
