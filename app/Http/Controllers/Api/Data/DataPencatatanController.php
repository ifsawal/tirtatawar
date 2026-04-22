<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Controller;
use App\Models\Master\Pencatatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mpdf\Tag\P;

class DataPencatatanController extends Controller
{
    public function petugas_pencatat(Request $request, $bulan, $tahun)  //laporan survey
    {
        $request->merge([
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        $this->validate($request, [
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);


        $user = User::whereIn('id', function ($query) use ($request) {
            $query->from('pencatatans')
                ->select('user_id')
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->distinct();
        })
            ->select('id', 'nama', 'email')
            ->get();

        return response()->json([
            'sukses' => true,
            'pesan' => "Data ditemukan...",
            'data' => $user,
        ], 202);
    }

    public function rekap_pencatatan_petugas(Request $request, $id, $bulan, $tahun)
    {
        $request->merge([
            'id' => $id,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        $this->validate($request, [
            'id' => 'required|integer',
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);


        $harian = Pencatatan::where('user_id', $request->id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->selectRaw("
        DATE(created_at) as tanggal,
        COUNT(*) as total,
        COUNT(CASE WHEN manual = 1 THEN 1 END) as manual,
        COUNT(CASE WHEN manual IS NULL THEN 1 END) as berpoto
    ")
            ->groupByRaw('DATE(created_at)')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($item) {
                $tgl = Carbon::parse($item->tanggal);

                $item->tanggal = $tgl->translatedFormat('l')." ".$tgl->format('d-m-Y');

                return $item;
            });

        $total = Pencatatan::where('user_id', $request->id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->selectRaw("
        COUNT(*) as total,
        COUNT(CASE WHEN manual = 1 THEN 1 END) as manual_1,
        COUNT(CASE WHEN manual IS NULL THEN 1 END) as manual_null
    ")
            ->first();

        return response()->json([
            'sukses' => true,
            'pesan' => "Data ditemukan...",
            'data' => $harian,
            'data2' => $total,
        ], 202);
    }
}
