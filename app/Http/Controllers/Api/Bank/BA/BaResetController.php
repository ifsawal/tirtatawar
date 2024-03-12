<?php

namespace App\Http\Controllers\Api\Bank\BA;



use Illuminate\Http\Request;
use App\Models\Master\Tagihan;
use App\Models\Master\Transfer;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pencatatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;



class BaResetController extends Controller
{
    public function reset(Request $r)
    {
        Log::info("Reset oleh " . $r->getClientIp());

        $urldasar = URL::to('/');


        if ($urldasar == "https://www.sandbox.tirtatawar.com" or $urldasar == "http://localhost:85/tirtatawar/public") {
        } else {
            return response()->json([
                "sukses" => false,
                "pesan" => "Enpoint ini hanya bisa di akses melakui Sandbox atau pengujian...",
                "kode"  => "03"
            ], 404);
        }



        DB::statement('SET FOREIGN_KEY_CHECKS=0;');




        DB::beginTransaction();
        try {

            for ($i = 1; $i <= 4; $i++) {

                $pelanggan = Pelanggan::whereId($i)
                    ->first();

                $pencatatan = Pencatatan::where('pelanggan_id', '=', $pelanggan->id)->get();
                foreach ($pencatatan as $p) {

                    $c = Pencatatan::where('id', '=', $p->id)->first();

                    if ($c->pelanggan_id == 1 && $c->bulan == 2 && $c->tahun = 2024) {
                    } else if ($c->pelanggan_id == 2 && ($c->bulan == 2 or $c->bulan == 1) && $c->tahun = 2024) {
                    } else if ($c->pelanggan_id == 3 && ($c->bulan == 2 or $c->bulan == 1) && $c->tahun = 2024) {
                    } else if ($c->pelanggan_id == 3 && ($c->bulan == 11) && $c->tahun = 2023) {
                    } else if ($c->pelanggan_id == 4) {
                    } else {
                        $c->forceDelete();
                    }


                    $tagihan = Tagihan::where('pencatatan_id', '=', $p->id)->first();
                    if ($tagihan && $tagihan->status_bayar == "Y" && $tagihan->sistem_bayar = "Transfer") {
                        $transfer = Transfer::where('tagihan_id', '=', $tagihan->id)
                            ->get();
                        foreach ($transfer as $t) {
                            $TF = Transfer::whereId($t->id)->first();
                            $TF->delete();
                        }

                        $tagihan->status_bayar = "N";
                        $tagihan->sistem_bayar = NULL;
                        $tagihan->tgl_bayar = NULL;
                        $tagihan->save();
                    }
                }
            }

            DB::commit();
            return response()->json([
                "sukses" => true,
                "pesan" => "Reset sukses...",
                "kode"  => "00"
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Reset Gagal...",
                "kode" => "03",
            ], 404);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
