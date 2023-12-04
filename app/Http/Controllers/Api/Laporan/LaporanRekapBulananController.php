<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Models\Master\Wiljalan;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use App\Models\Master\Rekap;
use Illuminate\Support\Facades\Auth;

class LaporanRekapBulananController extends Controller
{

    public function rekap(Request $r)
    {
    }
    public function proses_rekap(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required|integer',
        ]);
        $user_id = Auth::user()->id;
        $pdam_id = Auth::user()->pdam_id;

        $jalan = Wiljalan::where('pdam_id', $pdam_id)->get();
        $tampung = array();
        foreach ($jalan as $j) {
            $pel = Pelanggan::where('wiljalan_id', $j->id)->get()->count();

            $catat = Pencatatan::query();
            // $catat->select(
            //     'pelanggans.nama',
            // );
            $catat->join('pelanggans', 'pelanggans.id', '=', 'pencatatans.pelanggan_id');
            $catat->join('tagihans', 'tagihans.pencatatan_id', '=', 'pencatatans.id');
            $catat->where('pencatatans.tahun', '=', $r->tahun);
            $catat->where('pencatatans.bulan', '=', $r->bulan);
            $catat->where('pelanggans.wiljalan_id', '=', $j->id);
            // $catat->sum('pemakaian');

            $h_catat = $catat->get()->count();
            $h_total_pemakaian = $catat->sum('pencatatans.pemakaian');
            $h_harga_dasar = $catat->sum('tagihans.jumlah');
            $h_adm = $catat->sum('tagihans.biaya');
            $h_pajak = $catat->sum('tagihans.pajak');
            $h_total = $catat->sum('tagihans.total');

            $tampung[] = [
                "jalan" => $j->jalan,
                "pelanggan" => $pel,
                "catat" => $h_catat,
                "total_pemakaian" => $h_total_pemakaian,
                "harga_dasar" => $h_harga_dasar,
                "adm" => $h_adm,
                "pajak" => $h_pajak,
                "total" => $h_total,
            ];

            $rekap = Rekap::where('bulan', $r->bulan)
                ->where('tahun', $r->tahun)
                ->where('wiljalan_id', $j->id)
                ->first();
            if ($rekap) {
                $rekap->jumlah_pel = $pel;
                $rekap->jumlah_pel_catat = $h_catat;
                $rekap->pemakaian = $h_total_pemakaian;
                $rekap->harga_air = $h_harga_dasar;
                $rekap->adm = $h_adm;
                $rekap->pajak = $h_pajak;
                $rekap->total = $h_total;
                $rekap->save();
            } else {
                $rekap = new Rekap();
                $rekap->wiljalan_id = $j->id;
                $rekap->bulan = $r->bulan;
                $rekap->tahun = $r->tahun;
                $rekap->jumlah_pel = $pel;
                $rekap->jumlah_pel_catat = $h_catat;
                $rekap->pemakaian = $h_total_pemakaian;
                $rekap->harga_air = $h_harga_dasar;
                $rekap->adm = $h_adm;
                $rekap->pajak = $h_pajak;
                $rekap->total = $h_total;
                $rekap->save();
            }
        }



        return $tampung;
    }
}
