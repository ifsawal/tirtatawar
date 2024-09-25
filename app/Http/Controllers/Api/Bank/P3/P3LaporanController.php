<?php

namespace App\Http\Controllers\Api\Bank\P3;

use Illuminate\Http\Request;
use App\Models\Master\Transfer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class P3LaporanController extends Controller
{
    public function laporan($tanggal)
    {

        $user = Auth::user();
        $payload = request()->header();

        $data['tanggal'] = $tanggal;

        $validator = Validator::make($data, [
            'tanggal' => "required|date_format:Y-m-d",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status"    => false,
                "pesan" => $validator->errors(),
                "kode" => "02"
            ], 404);
        }


        $tandatangan = hash("sha256", $data['tanggal'] . $user->client_id);


        if (!isset($payload['tandatangan'][0]) or ($tandatangan <> $payload['tandatangan'][0])) {
            return response()->json([
                "status"    => false,
                "pesan" => "Tanda tangan tidak sah",
                "kode" => "02"
            ], 401);
        }


        $rekap = Transfer::query();
        $rekap->select(

            'transfers.bill_id',
            'transfers.vendor',
            'transfers.vendor_id_string',
            'transfers.status_bayar',
            'transfers.jumlah',
            'tagihans.tgl_bayar',
        );
        $rekap->join('tagihans', 'tagihans.id', '=', 'transfers.tagihan_id');
        $rekap->whereDate('tagihans.tgl_bayar', '=', $tanggal);
        $rekap->where('transfers.vendor', '=', "BA");
        $rekap->where('transfers.status_bayar', '=', "Y");
        $rekap->distinct('transfers.bill_id');

        $hasil = $rekap->get();
        $hasil = $hasil->map(function ($data, $key) {
            return [
                // "id" => encrypt($data->bill_id),
                "vendor" => $data->vendor,
                "vendor_id_string" => $data->vendor_id_string,
                "status_bayar" => $data->status_bayar,
                "jumlah" => $data->jumlah,
                "tanggal_bayar" => $data->tgl_bayar,
            ];
        });

        if (count($hasil) == 0) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Data tidak ditemukan...",
                "kode" => "03",
            ], 404);
        }
        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "kode"  => "00",
            "data"  => $hasil
        ], 200);
    }
}
