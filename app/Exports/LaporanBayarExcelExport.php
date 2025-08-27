<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class LaporanBayarExcelExport implements FromArray, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $r;

    function __construct($r)
    {
        $this->r = $r;
    }
    // public function collection()
    // {
    //     return $this->r;
    // }

    public function array():array
    {
        return $this->r;
    }
}
