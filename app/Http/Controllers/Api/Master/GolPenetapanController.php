<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use Illuminate\Support\Facades\DB;
use App\Models\Master\GolPenetapan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GolPenetapanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',

        ]);

        $cek = GolPenetapan::where('pelanggan_id', '=', $request->id)
            ->select('id', 'harga', 'pajak')
            ->where('aktif', '=', 'Y')
            ->first();

        $histori = GolPenetapan::with('user:id,nama', 'user_perubahan:id,nama')
            ->where('pelanggan_id', '=', $request->id)

            ->limit(10)->orderBy('id', 'DESC')
            ->get();


        return response()->json([
            'sukses' => true,
            'pesan' => "Data ditemukan...",
            'data_aktif' => $cek,
            'data_histori' => $histori,
        ], 202);
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
        $this->validate($request, [
            'id' => 'required',
            'harga' => 'required',
            'pajak' => 'required',
        ]);
        $user_id = Auth::user()->id;


        $sebelumnya = GolPenetapan::where('pelanggan_id', '=', $request->id)
            ->where('aktif', '=', 'Y')
            ->first();



        if ($sebelumnya) {

            DB::beginTransaction();
            try {
                $sebelumnya->aktif = "N";
                $sebelumnya->tgl_akhir = now();
                $sebelumnya->user_id_perubahan = $user_id;
                $sebelumnya->save();

                $penetapan = new GolPenetapan();
                $penetapan->pelanggan_id = $request->id;
                $penetapan->harga = $request->harga;
                $penetapan->pajak = $request->pajak;
                $penetapan->aktif = "Y";
                $penetapan->tgl_awal = now();
                $penetapan->user_id = $user_id;
                $penetapan->save();

                $pel = Pelanggan::findOrFail($request->id);
                $pel->penetapan = 1;
                $pel->save();

                DB::commit();
                // DB::rollback();
                return response()->json([
                    'sukses' => true,
                    'pesan' => "Perubahan harga berhasil...",

                ], 202);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Erorr..."
                ], 404);
            }
        }

        DB::beginTransaction();
        try {
            $penetapan = new GolPenetapan();
            $penetapan->pelanggan_id = $request->id;
            $penetapan->harga = $request->harga;
            $penetapan->pajak = $request->pajak;
            $penetapan->aktif = "Y";
            $penetapan->harga = $request->harga;
            $penetapan->tgl_awal = now();
            $penetapan->user_id = $user_id;
            $penetapan->save();

            $pel = Pelanggan::findOrFail($request->id);
            $pel->penetapan = 1;
            $pel->save();

            DB::commit();
            return response()->json([
                'sukses' => true,
                'pesan' => "Penambahan harga tetap berhasil...",
            ], 202);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "sukses" => false,
                "pesan" => "Erorr..."
            ], 404);
        }
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
    public function destroy(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',
        ]);
        $sebelumnya = GolPenetapan::where('pelanggan_id', '=', $r->id)
            ->where('aktif', '=', 'Y')
            ->first();
        $user_id = Auth::user()->id;

        if ($sebelumnya) {
            DB::beginTransaction();
            try {

                $sebelumnya->aktif = 'N';
                $sebelumnya->tgl_akhir = now();
                $sebelumnya->user_id_perubahan = $user_id;

                $sebelumnya->save();

                $pel = Pelanggan::findOrFail($r->id);
                $pel->penetapan = 0;
                $pel->save();

                DB::commit();
                return response()->json([
                    'sukses' => true,
                    'pesan' => "Sukses terhapus...",
                ], 204);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    "sukses" => false,
                    "pesan" => "Erorr..."
                ], 404);
            }
        }
    }
}
