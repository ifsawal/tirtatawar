<?php

namespace App\Http\Controllers\Api\Server;

use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;

class CekAngkaController extends Controller
{

    public function cek_rekap_total()
    {

        // $pel = Pelanggan::where('wiljalan_id', 35)->get()->count();

        $catat = Pencatatan::query();
        $catat->select(
            'pencatatans.id',
            'pencatatans.bulan',
            'pencatatans.tahun',
            'tagihans.jumlah',
            'tagihans.biaya',
            'tagihans.total',
            'tagihans.pajak',
            'tagihans.id as id_tagihan',
        );
        $catat->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
        $catat->join('tagihans', 'tagihans.pencatatan_id', '=', 'pencatatans.id');
        $catat->where('pencatatans.tahun', '=', 2023);
        $catat->where('pencatatans.bulan', '=', 11);
        $catat->where('pelanggans.wiljalan_id', '=', 35);
        // $catat->sum('pemakaian');

        $h_catat = $catat->get();

        $total = 0;
        $jumlah = 0;
        foreach ($h_catat as $b) {
            if ($b['total'] <> ($b['jumlah'] + $b['biaya'] + $b['pajak'])) {
                return $b['id'] . '-' . $b['id_tagihan'];
            }
            $total = $total + $b['total'];
            $jumlah = $jumlah + ($b['jumlah'] + $b['biaya'] + $b['pajak']);
        }
        return $total . "-" . $jumlah;
    }
}
