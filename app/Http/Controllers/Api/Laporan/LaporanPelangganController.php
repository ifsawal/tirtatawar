<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Exports\LaporanPelangganExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPelangganController extends Controller
{


    public function laporanpelangganexport(Request $r)
    {
        // isset($r->semuaakun) ? $user_id = '' : $user_id = Auth::user()->id;
        return Excel::download(new LaporanPelangganExport($r), 'laporan_data_pelanggan.xlsx');
    }


    public function laporan_pelanggan(Request $r)
    {
        // $this->validate($r, [
        //     'golongan_id' => 'required',
        //     'wiljalan_id' => 'required',
        // ]);

        $data = self::datapangkalan($r);

        if (count($data) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
            ], 404);
        }

        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses, data ditemukan...",
            "data" => $data,
        ], 202);
    }


    public static function datapangkalan($r, $cetak = "N")
    {

        $pdam = Auth::user();

        $rekap = Pelanggan::query();
        $rekap->select(
            'pelanggans.id',
            'pelanggans.nama',
            'pelanggans.nik',
            'pelanggans.lat',
            'pelanggans.long',
            'pelanggans.nolama',
            'golongans.golongan',
            'wiljalans.jalan as wilayah_jalan',
            'rutes.rute',
            'desas.desa',
            'kecamatans.kecamatan',


        );
        $rekap->join('golongans', 'golongans.id', '=', 'pelanggans.golongan_id');
        $rekap->join('wiljalans', 'wiljalans.id', '=', 'pelanggans.wiljalan_id');
        $rekap->join('rutes', 'rutes.id', '=', 'pelanggans.rute_id');
        $rekap->leftjoin('desas', 'desas.id', '=', 'pelanggans.desa_id');
        $rekap->leftjoin('kecamatans', 'kecamatans.id', '=', 'desas.kecamatan_id');
        if (isset($r->urut)) {
            $rekap->orderBy('pelanggans.' . $r->urut);
        } else {
            $rekap->orderBy('pelanggans.id');
        }
        $rekap->where('pelanggans.pdam_id', '=', $pdam->pdam_id);
        isset($r->golongan) ? $rekap->where('pelanggans.golongan_id', '=', $r->golongan) : "";
        isset($r->wiljalan) ? $rekap->where('pelanggans.wiljalan_id', '=', $r->wiljalan) : "";
        isset($r->rute) ? $rekap->where('pelanggans.rute_id', '=', $r->rute) : "";
        isset($r->desa) ? $rekap->where('pelanggans.desa_id', '=', $r->desa) : "";


        if ($cetak == "cetak") {
            return $rekap->get();
        }
        return $rekap->paginate(50);
    }
}
