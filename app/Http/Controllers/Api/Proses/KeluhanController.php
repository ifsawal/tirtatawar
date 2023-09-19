<?php

namespace App\Http\Controllers\Api\Proses;

use App\Models\Keluhan\Tim;
use Illuminate\Http\Request;
use App\Models\Keluhan\Proses;
use App\Models\Keluhan\Keluhan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\Proses\Keluhan\ListKeluhanReseource;

class KeluhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function detilkeluhan(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',
        ]);

        $kel = Keluhan::with(
            'pelanggan:id,nama,lat,long,nolama,wiljalan_id,desa_id,wiljalan_id,rute_id',
            'pelanggan.hp_pelanggan:id,nohp,pelanggan_id',
            'pelanggan.photo_rumah:id,pelanggan_id',
            'pelanggan.desa:id,desa,kecamatan_id',
            'pelanggan.desa.kecamatan:id,kecamatan',
            'pelanggan.rute:id,rute',
            'pelanggan.wiljalan:id,jalan',
        )
            ->Where('id',  $r->id)
            ->first();

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $kel,
        ], 202);
    }

    public function listkeluhan(Request $r)
    {
        $key = "status";
        $value = "proses";

        $key2 = "status";
        $value2 = NULL;



        if (isset($r->berdasarkan) && $r->berdasarkan == "selesai") {
            $value = $r->berdasarkan;
            $value2 = $r->berdasarkan;
        }

        if (isset($r->berdasarkan) && $r->berdasarkan == "wiljalan_id" or $r->berdasarkan == "desa_id") {
            $keyrelationpelanggan = $r->berdasarkan;
            $valuerelationpelanggan = $r->id;
            $operator = "=";
            $kel = ListKeluhanReseource::collection(Keluhan::with('pelanggan:id,nama,wiljalan_id,desa_id')
                ->whereRelation('pelanggan', $keyrelationpelanggan, $operator, $valuerelationpelanggan)
                // ->Where($key,  $value)
                // ->Where($key2,  $value2)
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get());
        } else {

            $kel = ListKeluhanReseource::collection(Keluhan::with('pelanggan:id,nama,wiljalan_id,desa_id')
                ->Where($key,  $value)
                ->orWhere($key2,  $value2)
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get());
        }
        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan...",
            "data" => $kel,
        ], 202);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function simpan_petugas(Request $r)
    {
        $this->validate($r, [
            'keluhan_id' => 'required',
            'user_id' => 'required',
            'proses' => 'required',
            'jabatan' => 'required',
        ]);
        $user_id = Auth::user()->id;


        $pro = Proses::where('keluhan_id', $r->keluhan_id)->first();

        DB::beginTransaction();

        try {
            if (!$pro) {
                $proses = new Proses();
                $proses->keluhan_id = $r->keluhan_id;
                $proses->user_id = $user_id;
                $proses->proses = $r->proses;
                $proses->save();
            }

            $tim = new Tim();
            $tim->keluhan_id = $r->keluhan_id;
            $tim->user_id = $r->user_id;
            $tim->status = $r->jabatan;
            $tim->save();

            DB::commit();

            return response()->json([
                "sukses" => true,
                "pesan" => "Berhasil tersimpan..."
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal..."
            ], 404);
        }
    }

    /**
     * Store a newly 
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
