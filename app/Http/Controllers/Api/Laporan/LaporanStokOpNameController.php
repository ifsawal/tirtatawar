<?php

namespace App\Http\Controllers\api\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanStokOpNameExport;

class LaporanStokOpNameController extends Controller
{
    public function download_stok_op_name(Request $r)
    {

        $this->validate($r, [
            'tahun' => 'required',
        ]);

        $akhirTahun = date('Y-m-t', strtotime($r->tahun . '-12-31'));

        return Excel::download(
            new LaporanStokOpNameExport($r->tahun),
            "laporan-tagihan-belum-bayar-$r->tahun.xlsx"
        );
    }
}
