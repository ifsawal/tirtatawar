<?php

namespace App\Http\Controllers\Api\Server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AtestController extends Controller
{
    public function test1()
    {
        $jawab = [];
        $a = NULL;
        if ($a == 0) {
            $jawab[] = "Sama antar NULL dan 0";
        }

        if ($a <> 0) {
            $jawab[] = "Tidak Sama antar NULL dan 0";
        } else $jawab[] = "Sama antara NULL dan 0";

        return response()->json([
            "status"    => false,
            "pesan" => $jawab,
            "kode" => "02"
        ], 202);
    }
}
