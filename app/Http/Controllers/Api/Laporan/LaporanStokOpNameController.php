<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanStokOpNameExport;

class LaporanStokOpNameController extends Controller
{
    public function download_stok_op_name(Request $r)
    {

        // jika kosong, set default ke tanggal sekarang
        if (empty($r['batas_data_diambil'])) {
            $r['batas_data_diambil'] = date('Y-m-d');
        }

        $this->validate($r, [
            'tahun' => 'required',
            'batas_data_diambil' => 'required|date|date_format:Y-m-d',
        ]);

        $akhirTahun = date('Y-m-t', strtotime($r->tahun . '-12-31'));

        return Excel::download(
            new LaporanStokOpNameExport($r->tahun, $r->batas_data_diambil),
            "laporan-tagihan-belum-bayar-$r->tahun.xlsx"
        );
    }
}
