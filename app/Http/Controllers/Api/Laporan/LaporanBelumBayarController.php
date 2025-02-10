<?php

namespace App\Http\Controllers\Api\Laporan;


use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanBelumBayarExport;
use Illuminate\Database\Eloquent\Builder;



class LaporanBelumBayarController extends Controller
{



    public function laporan_belum_bayar_export($tahun)
    {
        return Excel::download(new LaporanBelumBayarExport($tahun), 'laporan_belum_bayar.xlsx');
    }

    public static function laporan_belum_bayar($tahun)
    {
        // return Pelanggan::all()->count();
        // $pel = Pelanggan::with([
        //     'pencatatan' => function ($query) use ($tahun) {
        //         $query->where('tahun', '=', $tahun);
        //     },
        //     'golongan:id,golongan',
        //     'wiljalan:id,jalan',
        //     'pencatatan.tagihan',
        //     // 'pencatatan.tagihan' => function ($query) {
        //     //     $query->where('status_bayar', '=', 'N');
        //     // },
        //     'pencatatan.tagihan.penagih:id,user_id,tagihan_id,jumlah',
        //     'pencatatan.tagihan.penagih.user:id,nama'
        // ],)
        //     // ->where('id', 506)
        //     // ->whereRelation('pencatatan', 'tahun', '=', $tahun)
        //     // ->whereRelation('pencatatan.tagihan', 'status_bayar', '=', 'N')
        //     // ->limit(10)
        //     ->get();

        $pel = Pelanggan::with([
            'golongan:id,golongan',
            'wiljalan:id,jalan',
        ])

            ->get();


        $has = [];
        $kolom_awal = 4;

        $no = 0;
        foreach ($pel as $p) {
            $no++;




            $q = Pencatatan::query();
            $q->select(
                'pencatatans.bulan',
                'pencatatans.tahun',
                'tagihans.total_nodenda',
                'tagihans.status_bayar',
            );
            $q->join('tagihans', 'pencatatans.id', '=', 'tagihans.pencatatan_id');
            $q->where('pencatatans.tahun', '=', $tahun);
            $q->where('pencatatans.pelanggan_id', '=', $p->id);
            $hasil = $q->get();

            $b = array();
            for ($a = 1; $a <= 12; $a++) {

                $ditemukan = 0;
                foreach ($hasil as $h) {
                    if ($a == $h->bulan) {
                        if ($h->status_bayar == "N") {
                            $b[] = $h->total_nodenda;
                        } else {
                            $b[] = null;
                        }
                        $ditemukan = 1;
                    } else {
                        continue;
                    }
                }
                if ($ditemukan == 0) {
                    $b[] = null;
                }
            }
            $a = [
                'no' => $no,
                'nama' => $p->nama,
                'golongan' => $p->golongan->golongan,
                'wiljalan' => $p->wiljalan->jalan,
            ];

            $c['total'] = "=SUM(E$kolom_awal:P$kolom_awal)";
            $kolom_awal += 1;

            $has[] = $a + $b + $c;
        }
        return collect($has);
    }
}
