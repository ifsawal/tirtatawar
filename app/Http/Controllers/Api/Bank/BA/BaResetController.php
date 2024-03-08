<?php

namespace App\Http\Controllers\Api\Bank\BA;

use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Master\Pencatatan;
use App\Models\Master\Tagihan;
use App\Models\Master\Transfer;

class BaResetController extends Controller
{
    public function reset(){
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    

        DB::beginTransaction();
        try {

            for($i=1; $i<=4; $i++){

                $pelanggan=Pelanggan::whereId($i)
                ->first();
        
                $pencatatan=Pencatatan::where('pelanggan_id','=',$pelanggan->id)->get();
                foreach($pencatatan as $p){
        
                    $c=Pencatatan::where('id','=',$p->id)->first();
                    
                    if($c->pelanggan_id==1 && $c->bulan==2 && $c->tahun=2024){}
                    else if($c->pelanggan_id==2 && ($c->bulan==2 or $c->bulan==1) && $c->tahun=2024){}
                    else if($c->pelanggan_id==3 && ($c->bulan==2 or $c->bulan==1) && $c->tahun=2024){}            
                    else if($c->pelanggan_id==3 && ($c->bulan==11) && $c->tahun=2023){}            
                    else if($c->pelanggan_id==4){}            
                    else{
                        $c->forceDelete();
                    }
        
        
                    $tagihan=Tagihan::where('pencatatan_id','=',$p->id)->first();
                    if($tagihan && $tagihan->status_bayar=="Y" && $tagihan->sistem_bayar="Transfer"){
                        $transfer=Transfer::where('tagihan_id','=',$tagihan->id)
                        ->get();
                        foreach($transfer as $t){
                            $TF=Transfer::whereId($t->id)->first();
                            $TF->delete();
                        }
        
                        $tagihan->status_bayar="N";
                        $tagihan->sistem_bayar=NULL;
                        $tagihan->tgl_bayar=NULL;
                        $tagihan->save();
        
                    }
        
                }
        
                }

                DB::commit();
                return response()->json([
                    "sukses" => true,
                    "pesan" => "Reset sukses...",
                    "kode"  => 00
                ], 200);

        }catch (\Exception $e){
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Reset Gagal...",
                "kode" => 03,
            ], 404);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
