<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Models\Master\Rekap;
use Illuminate\Http\Request;
use App\Models\Master\Wiljalan;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanRekapBulananExport;

class LaporanRekapBulananController extends Controller
{

    public function laporanrekapbulananexport(Request $r)
    {
        return Excel::download(new LaporanRekapBulananExport($r), 'laporan_rekap_pencatatan_penagihan.xlsx');
    }


    public static function rekap($r, $cetak = "N")
    {

        // $pdam_id = Auth::user()->pdam_id;
        $pdam_id = 1;

        $rekap = Rekap::query();
        $rekap->select(
            'wiljalans.jalan',
            'rekaps.bulan',
            'rekaps.tahun',
            'rekaps.jumlah_pel',
            'rekaps.jumlah_pel_catat',
            'rekaps.pemakaian',
            'rekaps.harga_air',
            'rekaps.adm',
            'rekaps.pajak',
            'rekaps.total',
            'rekaps.status_update',
            'rekaps.terbayar',
            'rekaps.sisa',
            'rekaps.persentase',
            'rekaps.denda',
            'rekaps.den_terbayar',
        );
        $rekap->join('wiljalans', 'wiljalans.id', '=', 'rekaps.wiljalan_id');
        $rekap->where('rekaps.tahun', '=', $r->tahun);
        $rekap->where('rekaps.bulan', '=', $r->bulan);
        $rekap->where('rekaps.pdam_id', '=', $pdam_id);

        // $rekap = Rekap::where('bulan', $r->bulan)
        //     ->where('tahun', $r->tahun)
        //     ->where('pdam_id', $pdam_id)
        //     ->get();

        if ($cetak == "cetak") {
            return $rekap->get();
        }
        return $rekap->get();
    }


    public function ambil_rekap(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required|integer',
        ]);

        $rekap = self::rekap($r);

        if (count($rekap) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ], 404);
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses, data ditemukan...",
            "data" => $rekap,
        ], 202);
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
            $rekap = Rekap::where('bulan', $r->bulan)
                ->where('tahun', $r->tahun)
                ->where('wiljalan_id', $j->id)
                ->first();

            if ($rekap && $rekap->status_update == 1) {
                $tampung[] = [
                    "jalan" => $j->jalan,
                    "status_update" => "terkunci",
                ];
                continue;
            }

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
            $h_total = $catat->sum('tagihans.total_nodenda');

            $terbayar = 0;
            $sisa = 0;
            $pel_terbayar = 0;
            $denda=0;
            foreach ($catat->get() as $d) {
                if ($d->status_bayar == "Y") {
                    $terbayar += $d['total_nodenda'];
                    $pel_terbayar += 1;
                    $denda += $d['denda'];
                }else{
                    $sisa += $d['total_nodenda'];
                }
            }



            if ($rekap) {
                $rekap->jumlah_pel = $pel;
                $rekap->jumlah_pel_catat = $h_catat;
                $rekap->pelanggan_terbayar = $pel_terbayar;
                $rekap->pemakaian = $h_total_pemakaian;
                $rekap->harga_air = $h_harga_dasar;
                $rekap->adm = $h_adm;
                $rekap->pajak = $h_pajak;
                $rekap->total = $h_total;
                $rekap->terbayar = $terbayar;
                $rekap->sisa = $sisa;
                $rekap->persentase = $pel_terbayar===0?0:floor(($pel_terbayar*100)/$h_catat);
                $rekap->denda = $denda;
                $rekap->den_terbayar = $denda+$terbayar;
                $rekap->save();
            } else {
                $rekap = new Rekap();
                $rekap->wiljalan_id = $j->id;
                $rekap->bulan = $r->bulan;
                $rekap->tahun = $r->tahun;
                $rekap->jumlah_pel = $pel;
                $rekap->jumlah_pel_catat = $h_catat;
                $rekap->pelanggan_terbayar = $pel_terbayar;
                $rekap->pemakaian = $h_total_pemakaian;
                $rekap->harga_air = $h_harga_dasar;
                $rekap->adm = $h_adm;
                $rekap->pajak = $h_pajak;
                $rekap->total = $h_total;
                $rekap->pdam_id = $pdam_id;
                $rekap->terbayar = $terbayar;
                $rekap->sisa = $sisa;
                $rekap->persentase = $pel_terbayar===0?0:floor(($pel_terbayar*100)/$h_catat);
                $rekap->denda = $denda ;
                $rekap->den_terbayar = $denda+$terbayar;
                $rekap->save();
            }


            $tampung[] = [
                "jalan" => $j->jalan,
                "pelanggan" => $pel,
                "catat" => $h_catat,
                "total_pemakaian" => $h_total_pemakaian,
                "harga_dasar" => $h_harga_dasar,
                "adm" => $h_adm,
                "pajak" => $h_pajak,
                "total" => $h_total,
                "status_update" => $rekap->status_update,
            ];
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses, data ditemukan...",
            "data" => $tampung,
        ], 201);
    }
}
