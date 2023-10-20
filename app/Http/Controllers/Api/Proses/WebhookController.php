<?php

namespace App\Http\Controllers\Api\Proses;

use Illuminate\Http\Request;
use App\Models\Master\Transfer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Tagihan;

class WebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function callback(Request $r)
    {

        $data = isset($r->data) ? $r->data : null;
        $token = isset($r->token) ? $r->token : null;

        // $hasil = $token . "----" . $data;
        // $fp = fopen(public_path() . "/files2/tek.txt", "wb");
        // fwrite($fp, $hasil);
        // fclose($fp);
        if ($token <> config('external.token_flip')) {
            return "";
        }

        $data = json_decode($data);
        $status_bayar = ($data->status == "SUCCESSFUL") ? "Y" : "N";
        // return ($data);

        $transfer = Transfer::where('vendor_id', $data->bill_link_id)
            ->where('vendor', 'flip')
            ->get();




        DB::beginTransaction();
        try {
            foreach ($transfer as $tran) {
                Transfer::where('id', $tran->id)
                    ->update(['status_bayar' => $status_bayar, 'status_bayar_vendor' => $data->status]);

                if ($status_bayar == 'Y') {

                    //proses transfer pelanggan
                    if ($tran->tagihan_id <> NULL) {
                        Tagihan::where('id', $tran->tagihan_id)
                            ->update(['status_bayar' => 'Y', 'sistem_bayar' => 'Transfer', 'tgl_bayar' => date('Y-m-d H:i:s')]);
                    }

                    //proses transfer Pendaftaran baru
                    if ($tran->tabel_transfer == "Pelanggan") {
                        //bayar laen

                    }
                }
            }


            DB::commit();
            return response()->json([
                "sukses" => true,
                "pesan" => "Sukses...",
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal...",
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
