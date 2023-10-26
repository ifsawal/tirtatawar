<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class DownloadController extends Controller
{
    public function downloadadmin()
    {
        $path = public_path() . '/files2/down/tirtatawar.apk';
        return Response::download($path);
    }
    public function downloadpelanggan()
    {
        return view('singel.download');
        // $path = public_path() . '/files2/down/PDAM.apk';
        // return Response::download($path);
    }

    public function downloadpelangganolehadmin()
    {
        $path = public_path() . '/files2/down/PDAM.apk';
        return Response::download($path);
    }
}
