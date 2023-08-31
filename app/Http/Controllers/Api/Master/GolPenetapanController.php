<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Models\Master\GolPenetapan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GolPenetapanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        ]);
        $user_id = Auth::user()->id;

        $sebelumnya = GolPenetapan::where('pelanggan_id', '=', $request->id)
            ->where('aktif', '=', 'Y')
            ->first();
        if ($sebelumnya) {
            $sebelumnya->aktif = "N";
            $sebelumnya->tgl_akhir = now();
            $sebelumnya->user_id_perubahan = $user_id;
            $sebelumnya->save();

            $penetapan = new GolPenetapan();
            $penetapan->pelanggan_id = $request->id;
            $penetapan->harga = $request->harga;
            $penetapan->aktif = "Y";
            $penetapan->harga = $request->harga;
            $penetapan->tgl_awal = now();
            $penetapan->user_id = $user_id;
            $penetapan->save();

            return response()->json([
                'sukses' => true,
                'pesan' => "Perubahan harga berhasil...",
            ], 202);
        }

        $penetapan = new GolPenetapan();
        $penetapan->pelanggan_id = $request->id;
        $penetapan->harga = $request->harga;
        $penetapan->aktif = "Y";
        $penetapan->harga = $request->harga;
        $penetapan->tgl_awal = now();
        $penetapan->user_id = $user_id;
        $penetapan->save();

        return response()->json([
            'sukses' => true,
            'pesan' => "Penambahan harga tetap berhasil...",
        ], 202);
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
