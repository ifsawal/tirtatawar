<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Exports\LaporanMenunggakPetugasExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanMenunggakPetugasController extends Controller
{
    public function download_menunggak_petugas(Request $r)
    {

        // jika kosong, set default ke tanggal sekarang
        if (empty($r['batas_data_diambil'])) {
            $r['batas_data_diambil'] = date('Y-m-d');
        }

        $this->validate($r, [
            'batas_data_diambil' => 'required|date',
            'jenis' => 'required|in:aktif,hapus',
            'tahun' => 'required|integer|min:2020|max:2100',
        ]);

        $akhirTahun = date('Y-m-t', strtotime($r->tahun . '-12-31'));

        return Excel::download(
            new LaporanMenunggakPetugasExport($r),
            "laporan-menunggak-petugas.xlsx"
        );
    }
}
