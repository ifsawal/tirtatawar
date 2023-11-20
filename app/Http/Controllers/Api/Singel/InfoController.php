<?php

namespace App\Http\Controllers\Api\Singel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function info()
    {
        $pes = "
        <b>Aplikasi PDAM Versi 2</b>
        <br><br>
        Kritik dan saran <br>
        dapat disampaikan ke Kantor PDAM Tirta Tawar
        <br>Jl. Mahkamah, Asir Asir Asia, <br>
        Kec. Lut Tawar, Kabupaten Aceh Tengah, Aceh 24519
        <br>
        <br>
        atau dapat berpesan ke email<br>
        admin@tirtatawar.com<br>
        <br>

        Info kendala tekhnis <br>
        <b>IT Support PDAM Tirta Tawar</b><br>
        ifsawal@gmail.com

        
        ";

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $pes,
        ], 200);
    }


    public function versipelanggan()
    {
        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "versi" => 2,
        ], 202);
    }
}
