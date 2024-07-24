<?php

namespace App\Http\Controllers\Api\Laporan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;

class LaporanInputCatatanController extends Controller
{
    public function download_input_catatan(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);


        $bulan_sebelumnya = Carbon::parse($r->tahun . "-" . $r->bulan . "-1")->subMonthsNoOverflow()->format('n');
        $kurangtahun = Carbon::parse($r->tahun . "-" . $r->bulan . "-1")->subMonthsNoOverflow()->format('Y');  //kurangi tahun berdasarkan bulan

        $catat = Pencatatan::query();

        $catat->select(
            'pelanggans.id',
            'pelanggans.nama',
            'golongans.golongan',
            'wiljalans.jalan',
            'pencatatans.bulan',
            'pencatatans.tahun',
            'pencatatans.pemakaian',

        );




        $catat->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');

        $catat->join('golongans', 'pelanggans.golongan_id', '=', 'golongans.id');
        $catat->join('wiljalans', 'pelanggans.wiljalan_id', '=', 'wiljalans.id');


        // $catat->where('pencatatans.bulan', '=', $bulan_sebelumnya);
        $catat->where('pencatatans.bulan', '=', $r->bulan);

        $catat->where('pencatatans.tahun', '=', $r->tahun);
        // $catat->where('pencatatans.tahun', '=', $kurangtahun);
        
        isset($r->golongan_id) ? $catat->where('pelanggans.golongan_id', '=', $r->golongan_id) : '';
        isset($r->wiljalan_id) ? $catat->where('pelanggans.wiljalan_id', '=', $r->wiljalan_id) : '';
        
        


        // return $catat->get();
        return $catat->paginate(50);
    }
}
