<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Bayar;
use App\Models\Master\Jenisbayar;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    public function hapusbayar(Request $r)
    {
        $this->validate($r, [
            'id' => 'required', // bayar id
        ]);
        $user_id = Auth::user()->id;

        $pembayaran = Bayar::where('id', $r->id)
            ->where('user_id', $user_id)
            ->orderBy('id')
            ->first();
        if (!$pembayaran) {
            return response()->json([
                "sukses" => false,
                "pesan" => "Anda tidak dapat menghapus...",
                "kode" => 1,

            ], 202);
        }

        if ($pembayaran->status_bayar == "Y") {
            return response()->json([
                "sukses" => false,
                "pesan" => "Sudah Terbayar, dan tidak dapat terhapus...",
                "kode" => 2
            ], 202);
        }

        $pembayaran->delete();
        return response()->json([
            "sukses" => true,
            "pesan" => "Sukses terhapus...",
        ], 204);
    }



    public function simpanbayar(Request $r)
    {
        $this->validate($r, [
            'id' => 'required', // pelanggan id
            'jenisbayar_id' => 'required',
        ]);

        $jenis = Jenisbayar::findOrFail($r->jenisbayar_id)->first();
        $user_id = Auth::user()->id;

        $bayar = new Bayar();
        $bayar->pelanggan_id = $r->id;
        $bayar->jumlah = $jenis->jumlah;
        $bayar->jenisbayar_id = $r->jenisbayar_id;
        $bayar->status_bayar = "Y";
        $bayar->sistem_bayar = "Cash";
        $bayar->tgl_bayar = date('Y-m-d H:i:s');
        $bayar->user_id = $user_id;
        $bayar->save();

        return response()->json([
            "sukses" => true,
            "pesan" => "Tersimpan...",
        ], 201);
    }


    public function jenisbayar()
    {
        $user_id = Auth::user()->id;
        $user = User::with('pdam:id,pdam')->where('id', $user_id)->first();
        $jenis = Jenisbayar::where('pdam_id', $user->pdam->id)
            ->where('aktif', 'Y')
            ->get();

        $pembayaran = Bayar::with('jenisbayar:id,kegunaan')
            ->where('pelanggan_id', $user_id)
            ->orderBy('id')
            ->get();

        return response()->json([
            "sukses" => true,
            "pesan" => "Ditemukan...",
            "jenis" => $jenis,
            "pembayaran" => $pembayaran,

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
