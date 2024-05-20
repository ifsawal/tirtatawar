<?php


namespace App\Fungsi;


class Respon
{

    public static function respon($data)
    {
        if ( is_array($data) and  count($data) !== 0) {
            return response()->json([
                'sukses' => true,
                'pesan' => "Data ditemukan...",
                'data' => $data,
            ], 202);
        } else if ($data) {
            return response()->json([
                'sukses' => true,
                'pesan' => "Data ditemukan.",
                'data' => $data,
            ], 202);
        } else {
            return response()->json([
                'sukses' => false,
                'pesan' => "Data tidak ditemukan...",
            ], 404);
        }
    }

    public static function respon2($pesan){
        return response()->json([
            'sukses' => false,
            'pesan' => $pesan,
        ], 404);
    }


}
