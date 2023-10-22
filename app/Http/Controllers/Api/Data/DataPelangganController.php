<?php

namespace App\Http\Controllers\Api\Data;

use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\Data\DataPelangganResource;

class DataPelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'sumber' => 'required',
        ]);

        $pel = Pelanggan::query();
        if (isset($request->byuser)) {
            $user = Auth::user()->id;
            $pel->with('wiljalan:id,user_id,jalan');
            $pel->whereHas('wiljalan', function ($q) use ($user) {
                $q->where('user_id', '=', $user);
            });
        }
        $pel->where($request->sumber, '=', $request->id);


        $pel->get();
        $pel = new DataPelangganResource($pel->paginate((50)));

        return response()->json([
            'sukses' => true,
            'pesan' => "Data ditemukan...",
            'data' => $pel,
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
