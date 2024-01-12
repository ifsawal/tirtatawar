<?php

namespace App\Http\Controllers\Api\Laporan;

use Illuminate\Http\Request;
use App\Models\Master\Golongan;
use App\Models\Master\Wiljalan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LaporanRekapDrdGolonganController extends Controller
{
    public function drd_golongan(Request $r)
    {
        $this->validate($r, [
            'bulan' => 'required',
            'tahun' => 'required',
        ]);
        $user = Auth::user();

        return $gol = Golongan::all(['id', 'golongan']);
    }
}
