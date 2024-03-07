<?php

namespace App\Http\Controllers\Api\Bank\BA;

use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Models\Master\Transfer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BaStatusController extends Controller
{

    protected $data;
    protected $id_transaksi;
    protected $no_trx;
    protected $status;
    protected $waktu;


    protected $checksum;
    protected $payload;
    
    public function status(Request $r){
        $user = Auth::user();
        $this->payload = request()->header();


        $data = $r->getContent();
        $data = json_decode($data, true);
        $this->id_transaksi     = $data['id_transaksi'];
        $this->no_trx          = $data['no_trx'];
        $this->status          = $data['status'];
        $this->waktu            = $data['waktu'];

        $this->checksum = hash("sha256", $this->id_transaksi.$this->no_trx.$this->status.$this->waktu.$user->client_id);

        if (!isset($this->payload['tandatangan'][0]) or ($this->checksum <> $this->payload['tandatangan'][0]) ) {
            return response()->json([
                "status"    =>false,
                "pesan" => "Tanda tangan tidak sah",
                "kode" => 02
            ], 401);
        }

        $id_trx_dasar=decrypt($this->id_transaksi);

        $transfer=Transfer::where("bill_id",$id_trx_dasar)->get();


        DB::beginTransaction();
        try {
        foreach ($transfer as $tran){
            Transfer::where('id', $tran->id)
            ->update(['status_bayar' =>  $this->status, 'status_bayar_vendor' => $this->no_trx]);

        if ($this->status == 'Y') {

            //proses transfer pelanggan
            if ($tran->tagihan_id <> NULL) {
                Tagihan::where('id', $tran->tagihan_id)
                    ->update(['status_bayar' => 'Y', 'sistem_bayar' => 'Transfer', 'tgl_bayar' => date('Y-m-d H:i:s')]);
            }

        }
    }
    


    DB::commit();
    return response()->json([
        "sukses" => true,
        "pesan" => "Sukses...",
        "kode"  => 00
    ], 200);

} catch (\Exception $e) {
    DB::rollback();
    return response()->json([
        "sukses" => false,
        "pesan" => "Gagal...",
        "kode" => 03,
    ], 404);
}


    }





    }