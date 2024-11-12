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



    public function laporan_belum_bayar_export()
    {
        $tahun=2024;
        return Excel::download(new LaporanBelumBayarExport($tahun), 'laporan_belum_bayar.xlsx');
    }
    
    public static function laporan_belum_bayar($tahun)
    {
        $bulan=11;
        return $pel = Pelanggan::with(
            'golongan:id,golongan',
            'wiljalan:id,jalan',
            'pencatatan:id,bulan,tahun,pelanggan_id',
            'pencatatan.tagihan:id,total,status_bayar,sistem_bayar,pencatatan_id',
            'pencatatan.tagihan.penagih:id,user_id,tagihan_id,jumlah'
        )
            ->whereHas('pencatatan', function (Builder $query) use ($bulan) {
                $query->where('bulan', '=', $bulan);
            })
            // ->whereRelation('pencatatan', 'bulan', '=', 1)
            ->limit(10)
            ->get();
    }


}
