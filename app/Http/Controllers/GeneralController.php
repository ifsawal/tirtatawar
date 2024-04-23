<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function hapus_akun()
    {
        // $log=Auth::user()->nama;
        return view('hapus_akun');
    }

    public function proses_hapus_akun(Request $r)
    {
        $this->validate($r, [
            'idpel' => 'required',
        ]);


        return "<center><br><br><br>Pengajuan Hapus AKun anda akan diproses...<br><br>selanjutnya silahkan hub. Kantor PDAM untuk konfirmasi pemutusan pelanggan";
    }
}
