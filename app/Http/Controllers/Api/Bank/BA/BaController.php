<?php

namespace App\Http\Controllers\Api\Bank\BA;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BaController extends Controller
{
    protected $nopel;


    protected $checksum;
    protected $payload;


    public function __construct()
    {
    }

    public function tagihan($nopel)
    {
        $val = "/^\\d+$/";

        $validasi = preg_match($val, $nopel); //cek hny angka
        if ($validasi == 0) {
            return response()->json([
                "message" => "Nopel salah",
            ], 422);
        }

        $user = Auth::user();
        $this->payload = request()->header();
        $this->checksum = hash("sha256", $nopel . $user->client_id);
        $this->nopel = $nopel;

        if ($this->checksum <> $this->payload['tandatangan'][0]) {
            return response()->json([
                "pesan" => "Tanda tangan tidak sah",
            ], 401);
        }

        if ($nopel >= 4) {
            return response()->json([
                "status" => false,
                "pesan" => "Data tidak ditemukan",
            ], 404);
        }
    }
}
