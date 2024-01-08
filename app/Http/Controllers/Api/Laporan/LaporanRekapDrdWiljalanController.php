<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Models\Master\Wiljalan;
use App\Models\Master\Pelanggan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LaporanRekapDrdWiljalanController extends Controller
{
    public function drd_wiljalan(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        $user = Auth::user();

        $wiljalan = Wiljalan::all();
        $pel = [];
        foreach ($wiljalan as $wil) {
            $data = Pelanggan::query();
            $data->select(
                'pelanggans.id',
                'pelanggans.nama',
                'pencatatans.bulan',
                'pencatatans.tahun',
                'tagihans.total',
            );
            $data->join('pencatatans', 'pencatatans.pelanggan_id', '=', 'pelanggans.id');
            $data->join('tagihans', 'tagihans.pencatatan_id', '=', 'pencatatans.id');

            $data->where('pelanggans.wiljalan_id', '=', $wil['id']);


            $data->where('pencatatans.tahun', '=', $r->tahun);
            $data->where('pencatatans.bulan', '=', $r->bulan);
            $data->where('pelanggans.pdam_id', '=', $user->pdam_id);
            $hasil = $data->get();

            $total = 0;
            foreach ($hasil as $h) {
                $total += $h['total'];
            }

            $pel[] = [
                "id"            => $wil['id'],
                "wiljalan"      => $wil['jalan'],
                "jumlah_pelanggan"        => $data->get()->count(),
                "total"        => $total,

            ];
        }

        return $pel;
    }
}
