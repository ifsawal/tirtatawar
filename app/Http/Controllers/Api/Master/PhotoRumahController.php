<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\PhotoRumah;
use Illuminate\Http\Request;

class PhotoRumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function potorumahpelanggan($id)
    {
        $p = PhotoRumah::where('pelanggan_id', $id)->get(['id', 'pelanggan_id']);

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan",
            "jumlah_photo" => count($p),
            "data" => $p,
        ], 200);
    }


    /**
     * Show the form for creating a new resource. fdsafds
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
