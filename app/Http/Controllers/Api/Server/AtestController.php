<?php

namespace App\Http\Controllers\Api\Server;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AtestController extends Controller
{
    public function test1()
    {

        $sekaran = Carbon::parse("2024-2-28")->format('Y-m');
        $beda = date('Y-m', strtotime(Carbon::parse("2024-02-28")->subMonthsNoOverflow(1)->format('Y-m')));

        return $sekaran . ":" . $beda;



        // $jawab = [];
        // $a = NULL;
        // if ($a == 0) {
        //     $jawab[] = "Sama antar NULL dan 0";
        // }

        // if ($a <> 0) {
        //     $jawab[] = "Tidak Sama antar NULL dan 0";
        // } else $jawab[] = "Sama antara NULL dan 0";

        // return response()->json([
        //     "status"    => false,
        //     "pesan" => $jawab,
        //     "kode" => "02"
        // ], 202);
    }
}
