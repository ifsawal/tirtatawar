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

        $pel = Pelanggan::with([
            'pencatatan' => function ($query) use ($tahun) {
                $query->where('tahun', '=', $tahun);
            },
            'golongan:id,golongan',
            'wiljalan:id,jalan',
            'pencatatan.tagihan',
            'pencatatan.tagihan' => function ($query) {
                $query->where('status_bayar', '=', 'N');
            },
            'pencatatan.tagihan.penagih:id,user_id,tagihan_id,jumlah',
            'pencatatan.tagihan.penagih.user:id,nama'
        ],)
            // ->where('id', 1)
            ->whereRelation('pencatatan', 'tahun', '=', $tahun)
            ->whereRelation('pencatatan.tagihan', 'status_bayar', '=', 'N')
            // ->limit(15)
            ->get();







        $has = [];
        $kolom_awal=4;   

        $no=0;
        foreach ($pel as $p) {
            $no++;

            $a = [
                'no' => $no,
                'nama' => $p->nama,
                'golongan' => $p->golongan->golongan,
                'wiljalan' => $p->wiljalan->jalan,
            ];

            $b=[];
            for ($i = 0; $i < 12; $i++) {
                if (isset($p->pencatatan[$i]->tagihan->total_nodenda)) {
                    $b[$i] = $p->pencatatan[$i]->tagihan->total_nodenda;
                } else {
                    $b[$i] = null;
                }
            }
                     
            $c['total'] = "=SUM(E$kolom_awal:P$kolom_awal)";
            $kolom_awal+=1;

            $has[] = $a + $b+$c;




        }
        return collect($has);
    }
}
