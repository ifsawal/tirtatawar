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



    public function laporan_belum_bayar_export(Request $r, $tahun)
    {
        if (empty($r['batas_data_diambil'])) {
            $r['batas_data_diambil'] = date('Y-m-d');
        }

        return Excel::download(new LaporanBelumBayarExport($tahun, $r->batas_data_diambil), 'laporan_belum_bayar.xlsx');
    }

    public static function laporan_belum_bayar($tahun, $batas_data_diambil)
    {

        $batas_akhir = date("Y-m-t", strtotime($batas_data_diambil));

        $pel = Pelanggan::with([
            'golongan:id,golongan',
            'wiljalan:id,jalan',
        ])
            // ->limit(10)

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
                'tagihans.tgl_bayar',
            );
            $q->join('tagihans', 'pencatatans.id', '=', 'tagihans.pencatatan_id');
            // $q->where(function ($query) use ($batas_akhir) {
            //     $query->whereDate('tagihans.tgl_bayar', '<=', $batas_akhir)
            //         ->orWhereNull('tagihans.tgl_bayar');
            // });
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
                        } else if ($h->status_bayar == "Y" && $h->tgl_bayar &&  date('Y-m-d', strtotime($h->tgl_bayar)) > $batas_akhir) {
                            $b[] = $h->total_nodenda;
                        }
                         else 
                        {
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
                'no' => $p->id,
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
