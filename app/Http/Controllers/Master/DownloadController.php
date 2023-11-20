<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
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
        return Redirect::to('https://play.google.com/store/apps/details?id=com.pdam.pdam');

        // return view('singel.download'); //menuju ke link halaman


        // $path = public_path() . '/files2/down/PDAM.apk'; //jika download dari website
        // return Response::download($path);
    }

    public function downloadpelangganolehadmin()
    {
        $path = public_path() . '/files2/down/PDAM.apk';
        return Response::download($path);
    }
}
