<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Models\Master\Drdjalan;
use App\Models\Master\Golongan;
use App\Models\Master\Wiljalan;
use App\Models\Master\Pelanggan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanRekapDrdWiljalanExport;

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
        $gol = Golongan::all(['id', 'golongan']);

        $pel = [];
        foreach ($wiljalan as $wil) {

            // $tampung_gol = [];



            foreach ($gol as $g) {
                $data = Pelanggan::query();
                $data->select(
                    'pelanggans.id',
                    'pelanggans.nama',
                    'pelanggans.golongan_id',
                    'pencatatans.bulan',
                    'pencatatans.tahun',
                    'pencatatans.pemakaian',
                    'tagihans.jumlah',
                    'tagihans.total',
                );
                $data->join('pencatatans', 'pencatatans.pelanggan_id', '=', 'pelanggans.id');
                $data->join('tagihans', 'tagihans.pencatatan_id', '=', 'pencatatans.id');
                $data->where('pelanggans.wiljalan_id', '=', $wil['id']);
                $data->where('pelanggans.golongan_id', '=', $g['id']);
                $data->where('pencatatans.tahun', '=', $r->tahun);
                $data->where('pencatatans.bulan', '=', $r->bulan);
                $data->where('pelanggans.pdam_id', '=', $user->pdam_id);
                $hasil = $data->get();
                $jum_pelanggan = $data->get()->count();
                $total = 0;
                $pemakaian = 0;
                foreach ($hasil as $h) {
                    $pemakaian += $h['pemakaian'];
                    $total += $h['total'];
                }

                $drd = Drdjalan::where('bulan', $r->bulan)
                    ->where('tahun', $r->tahun)
                    ->where('wiljalan_id', $wil['id'])
                    ->where('golongan_id', $g['id'])
                    ->first();
                if (!$drd) {
                    $drd = new Drdjalan();
                }

                $drd->rute_id = NULL;
                $drd->wiljalan_id = $wil['id'];
                $drd->golongan_id = $g['id'];
                $drd->bulan = $r->bulan;
                $drd->tahun = $r->tahun;
                $drd->jumpel = $jum_pelanggan;
                $drd->jumm3 = $pemakaian;
                $drd->jumtotal = $total;
                $drd->save();

                $tampung_gol[] = [
                    "golongan_id"               => $g['id'],
                    "golongan"                  => $g['golongan'],
                    "jumlah_pelanggan"          => $jum_pelanggan,
                    "pemakaian"                 => $pemakaian,
                    "total"                     => $total,


                ];
            }





            $t_pel = 0;
            $t_total = 0;
            $t_pemakaian = 0;
            foreach ($tampung_gol as $tam) {
                $t_pemakaian += $tam['pemakaian'];
                $t_pel += $tam['jumlah_pelanggan'];
                $t_total += $tam['total'];
            }

            $pel[] = [
                "id"                => $wil['id'],
                "wiljalan"          => $wil['jalan'],
                "jumlah_pelanggan"  => $t_pel,
                "total"             => $t_total,
                "pemakaian"         => $t_pemakaian,
                "per_gol"           => $tampung_gol,

            ];
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses...",
            // "data" =>  $pel,
        ], 202);
    }

    public function laporanrekapdrdwiljalanexport(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        return Excel::download(new LaporanRekapDrdWiljalanExport($r), 'laporan_rekap_DRDwiljalan.xlsx');
    }


    public static function urutkan_array(array $multiDimArray): array
    {
        $flatten = [];

        $singleArray = array_map(function ($arr) use (&$flatten) {
            $flatten = array_merge($flatten, $arr);
        }, $multiDimArray);

        return $flatten;
    }


    public static function export_drd_wilayah(Request $r, $wiljalan, $gol)
    {

        $user = Auth::user();

        $no = 0;
        $tampung_jalan = [];
        foreach ($wiljalan as $w) {
            $no += 1;
            $tampung_gol = [];

            $satu = 0;
            $dua = 0;
            $tiga = 0;
            foreach ($gol as $g) {
                $lap = Drdjalan::where('wiljalan_id', $w['id'])
                    ->where('golongan_id', $g['id'])
                    ->where('bulan', $r['bulan'])
                    ->where('tahun', $r['tahun'])
                    ->first(['jumpel', 'jumm3', 'jumtotal']);
                $satu += $lap->jumpel;
                $dua += $lap->jumm3;
                $tiga += $lap->jumtotal;
                $tampung_gol[] = [
                    $lap->jumpel,
                    $lap->jumm3,
                    $lap->jumtotal,
                ];
            }

            $k = self::urutkan_array($tampung_gol);

            $tt = [
                "no" => $no,
                "jalan" => $w['jalan'],
            ];

            for ($a = 0; $a < count($k); $a++) {
                array_push($tt, $k[$a]);
            }

            //TAMPIL HASIL TOTA
            array_push($tt, $satu);
            array_push($tt, $dua);
            array_push($tt, $tiga);

            $tampung_jalan[] = $tt;
        }
        return collect($tampung_jalan);
    }
}
