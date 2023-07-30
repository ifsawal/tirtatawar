<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Master\Pelanggan;
use App\Models\Master\HpPelanggan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function setujui(Request $request)
    {
        $user_id=Auth::user()->id;
        $pelanggan=Pelanggan::withTrashed()->findOrFail($request->id);
        $pelanggan->restore();
        $pelanggan->user_id_penyetuju=$user_id;
        $pelanggan->save();
        
        return response()->json([
            'sukses' => true,
            'pesan' => "Pendaftaran berhasil...",
            'id' => $pelanggan->id,
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
            'nama' => 'required|min:4',
            'nik' => 'required|min:16',
            'pdam_id' => 'required',
            'desa_id' => 'required',
            'user_id' => 'required',
        ]);

        $pelanggan = new Pelanggan;
        $pelanggan->nama=$request->nama;
        $pelanggan->nik=$request->nik;
        $pelanggan->pdam_id=$request->pdam_id;
        $pelanggan->desa_id=$request->desa_id;
        $pelanggan->user_id=$request->user_id;
        $pelanggan->deleted_at=Carbon::now();
        $pelanggan->save();

        if(isset($request->nohp)){
            $nohp= new HpPelanggan;
            $nohp->nohp=$request->nohp;
            $nohp->pelanggan_id=$pelanggan->id;
            $nohp->aktif="Y";
            $nohp->save();
        }

        return response()->json([
            'sukses' => true,
            'pesan' => "Pendaftaran berhasil...",
            'id' => $pelanggan->id,
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

        $this->validate($request, [
            'nama' => 'required|min:4',
            'nik' => 'required|min:16',
            'desa_id' => 'required',
            'user_id' => 'required',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->nama=$request->nama;
        $pelanggan->nik=$request->nik;
        $pelanggan->desa_id=$request->desa_id;
        $pelanggan->user_id=$request->user_id;
        $pelanggan->user_id_perubahan=Auth::user()->id;
        $pelanggan->save();

        if(isset($request->nohp)){
            $nohp= new HpPelanggan;
            $nohp->nohp=$request->nohp;
            $nohp->pelanggan_id=$pelanggan->id;
            $nohp->aktif="Y";
            $nohp->save();
        }

        return response()->json([
            'sukses' => true,
            'pesan' => "Perubahan berhasil...",
            'id' => $pelanggan->id,
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
