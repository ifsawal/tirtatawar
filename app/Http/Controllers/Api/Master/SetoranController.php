<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Setoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetoranController extends Controller
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
        //
    }

    public function rubah(Request $r)
    {

        $this->validate($r, [
            'id' => 'required',  //id setoran
        ]);


        $setoran = Setoran::findOrFail($r->id);
        if ($setoran->diterima == "1") {
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal karena Uang sudah diserahkan sebelumnya...",
            ], 404);
        }


        $setoran->user_id_diserahkan = $r->user;
        $setoran->save();



        return response()->json([
            "sukses" => true,
            "pesan" => "Berhasil diserahkan...",
        ], 201);
    }



    public function setujuiditerima(Request $r)
    {
        $this->validate($r, [
            'id' => 'required',  //id setoran
        ]);

        $setoran = Setoran::findOrFail($r->id);

        $user_id = Auth::user()->id;
        if ($setoran->user_id_diserahkan <> $user_id) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Gagal mencatat...",
            ], 404);
        }


        $setoran->diterima = 1;
        $setoran->save();

        return response()->json([
            "sukses" => true,
            "pesan" => "Tanda terima sukses dicatat...",
        ], 201);
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
