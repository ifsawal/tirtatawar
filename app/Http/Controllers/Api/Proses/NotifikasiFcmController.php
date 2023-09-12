<?php

namespace App\Http\Controllers\Api\Proses;

use App\Fungsi\Notif;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifikasiFcmController extends Controller
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
    public function notif()
    {
        $ke = "eD_gvjvjQrugyc0Jl-tLNZ:APA91bG43Ra9kvROKcls-Ft8ntNABk_IAcSNNPEj5AIp6_mXPEFD8hlf4tW6dN-cBu-VrpAEpNPq9jwbd-Cw9YqIS4lnNci26mhQASBUZeU2zW_cS9ScQ39JpIlYNnFcjYN9HboRMAOF";


        return Notif::Kirim($ke, 'Tes', 'Hal /n ooo <br> ww \n w  \r\n dfsfs', 'ic_launcher');
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
