<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Provinsi;
use Illuminate\Http\Request;

class AlamatController extends Controller
{
    public function index()
    {
        $provinsi = Provinsi::all();
        return view('master.alamat', compact('provinsi'));
    }
}
