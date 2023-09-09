<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class PhotoCatatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function potocatatan($id)
    {
        $p = Pencatatan::where('id', $id)->select(['id', 'photo', 'tahun', 'bulan'])->first();

        return response()->json([
            "sukses" => true,
            "pesan" => "Data ditemukan",
            "data" => $p,
        ], 202);
    }


    /**
     * Show the form for creating a new resource. fdsafds
     */
    public function tampilphotoc($tahun, $bulan, $photo)
    {
        $path = public_path() . '/files2/pencatatan/' . $tahun . '/' . $bulan . '/' . $photo;
        return Response::download($path);
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
