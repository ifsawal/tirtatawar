<?php

namespace App\Http\Controllers\Api\Absen;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanAbsensiExport;
use App\Exports\LaporanKegiatanExport;

class LaporanAbsenController extends Controller
{
    public function rekap(Request $request, $bulan, $tahun)
    {

        if ($bulan >= 1 && $bulan <= 12 && $tahun >= 1900 && $tahun <= date('Y')) {
            // valid
        } else {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Format bulan atau tahun salah...',
            ], 400);
        }

        // return User::with(['absen' => function ($q) use ($bulan, $tahun) {
        //     $q->whereMonth('tanggal', $bulan)
        //         ->whereYear('tanggal', $tahun);
        // }])->get();

        return Excel::download(new LaporanAbsensiExport($bulan, $tahun), "absensi-{$bulan}-{$tahun}.xlsx");
    }

    public function rekap_kegiatan(Request $request, $bulan, $tahun)
    {

        if ($bulan >= 1 && $bulan <= 12 && $tahun >= 1900 && $tahun <= date('Y')) {
            // valid
        } else {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Format bulan atau tahun salah...',
            ], 400);
        }

        // return User::with(['absen' => function ($q) use ($bulan, $tahun) {
        //     $q->whereMonth('tanggal', $bulan)
        //         ->whereYear('tanggal', $tahun);
        // }])->get();

        return Excel::download(new LaporanKegiatanExport($bulan, $tahun), "kegiatan-{$bulan}-{$tahun}.xlsx");
    }
}
