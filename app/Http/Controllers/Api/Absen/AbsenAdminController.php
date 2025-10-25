<?php

namespace App\Http\Controllers\Api\Absen;

use Carbon\Carbon;
use App\Models\Master\Absen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Absen\AbsenAdminResource;

class AbsenAdminController extends Controller
{
    public function absen_admin(Request $r, $tanggal)
    {
        
        $validator = Validator::make(['id' => $tanggal], [
            'id' => ['required', 'date_format:Y-m-d'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => false,
                'pesan' => 'Format id tidak valid. Harus YYYY-MM-DD.',
                'errors' => $validator->errors(),
            ], 422);
        }

         $absen = Absen::with('user')
         ->where('tanggal', $tanggal)
            ->get();

        $tanggalterpilih = $tanggal ? Carbon::parse($tanggal) : null;

        return response()->json([
            'sukses' => true,
            'data' => AbsenAdminResource::collection($absen),
            'hari' => $tanggalterpilih ? $tanggalterpilih->locale('id')->isoFormat('dddd') : null,
            'tanggal' => date('d-m-Y', strtotime($tanggal))
        ], 202);
    }
}
