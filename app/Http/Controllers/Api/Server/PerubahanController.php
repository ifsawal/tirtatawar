<?php

namespace App\Http\Controllers\Api\Server;

use App\Http\Controllers\Controller;
use App\Models\Master\Pelanggan;
use Illuminate\Http\Request;

class PerubahanController extends Controller
{
    public function rubah_petugas(Request $r)
    {

        if (isset($r->awal) && isset($r->akhir)) {
            $a = Pelanggan::where('wiljalan_id', $r->wiljalan_id)
                ->where('id', '>', $r->awal)
                ->where('id', '<', $r->akhir)
                // ->update(['user_id'=>$r->petugas]);
                ->update(['user_id_petugas' => $r->petugas]);
        } else {
            $a = Pelanggan::where('wiljalan_id', $r->wiljalan_id)
                // ->update(['user_id'=>$r->petugas]);
                ->update(['user_id_petugas' => $r->petugas]);
        }


        return $a;
    }
}
