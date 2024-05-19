<?php


namespace App\Fungsi;


class Respon
{

    public static function respon($data)
    {
        if (count($data) !== 0) {
            return response()->json([
                'sukses' => true,
                'pesan' => "Data ditemukan...",
                'data' => $data,
            ], 202);
        } else {
            return response()->json([
                'sukses' => false,
                'pesan' => "Data tidak ditemukan...",
            ], 404);
        }
    }
}
