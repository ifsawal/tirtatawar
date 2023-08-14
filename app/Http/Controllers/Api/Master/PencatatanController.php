<?php

namespace App\Http\Controllers\Api\Master;

use Illuminate\Http\Request;
use App\Models\Master\Pencatatan;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PencatatanController extends Controller
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
            'akhir' => 'required',
            'photo' => 'required',
            'pelanggan_id' => 'required',
        ]);

        $user_id = Auth::user()->id;
        $sebelumnya = Pencatatan::where('pelanggan_id', '=', $request->pelanggan_id)->latest('id')->first('akhir', 'created_at');
        if (!$sebelumnya) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Meteran awal tidak ditemukan",
                "kode" => 0,
            ], 404);
        }

        $awal = $sebelumnya->akhir;
        $pemakaian = $request->akhir - $awal;
        //jika minus
        if ($pemakaian < 0) {
            return "minus";
        }




        $waktu = Carbon::now();
        $bulan = $waktu->month;
        $tahun = $waktu->year;


        // $pencatatan =  new Pencatatan();
        // $pencatatan->awal = $awal; //
        // $pencatatan->akhir = $request->akhir; //
        // $pencatatan->pemakaian = $pemakaian; //
        // $pencatatan->bulan = $bulan; //
        // $pencatatan->tahun = $tahun; //
        // $pencatatan->photo = "96.844175600882";
        // $pencatatan->pelanggan_id = $request->pelanggan_id;
        // $pencatatan->user_id = $user_id; //
        // $pencatatan->save();
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
