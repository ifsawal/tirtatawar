<?php

namespace App\Http\Controllers\Master;


use Exception;
use App\Models\Master\Desa;
use Illuminate\Http\Request;
use App\Models\Master\Provinsi;
use App\Models\Master\Kabupaten;
use App\Models\Master\Kecamatan;
use PHPUnit\Event\Code\Throwable;
use App\Http\Controllers\Controller;

class AlamatController extends Controller
{
    public function index()
    {
        $provinsi = Provinsi::all();
        return view('master.alamat', compact('provinsi'));
    }

    public function simpan_provinsi(Request $request)
    {

        try {
            $data = $request->validate([
                'nama' => 'required',
            ]);
            $provinsi = new Provinsi();
            $provinsi->provinsi = $request->nama;
            $provinsi->save();
            return "Sukses";
        } catch (Exception $e) {
            return "$e";
        }
    }

    public function hapus_provinsi(Request $request)
    {
        $id = decrypt($request->id);

        try {
            $data = $request->validate([
                'id' => 'required',
            ]);
            $provinsi = Provinsi::findOrFail($id);
            $provinsi->delete();
            return "Sukses";
        } catch (Exception $e) {
            return "$e";
        }
    }

    public function get_kecamatan(Request $request)
    {
        $id = $request->id;
        $kec = Kecamatan::where('kabupaten_id', '=', $id)->get();
        return response()->json($kec);
    }

    public function simpan_kecamatan(Request $request)
    {
        $id_kab=$request->id_kab;
        try {
            $data = $request->validate([
                'nama' => 'required',
                'id_kab'=>'required',
            ]);
            $kecamatan = new Kecamatan();
            $kecamatan->kecamatan = $request->nama;
            $kecamatan->kabupaten_id = $id_kab;
            $kecamatan->save();
            return "Sukses";
        } catch (Exception $e) {
            return "$e";
        }
    }

    public function hapus_kecamatan(Request $request)
    {
        $id = $request->id;

        try {
            $data = $request->validate([
                'id' => 'required',
            ]);
            $kabupaten = Kecamatan::findOrFail($id);
            $kabupaten->delete();
            return "Sukses";
        } catch (Exception $e) {
            return "$e";
        }
    }


    public function get_desa(Request $request)
    {
        $id = $request->id;
        $desa = Desa::where('kecamatan_id', '=', $id)->get();
        return response()->json($desa);
    }

    public function simpan_desa(Request $request)
    {
        $id_kec=$request->id_kec;
        try {
            $data = $request->validate([
                'nama' => 'required',
                'id_kec'=>'required',
            ]);
            $desa = new Desa();
            $desa->desa = $request->nama;
            $desa->kecamatan_id = $id_kec;
            $desa->save();
            return "Sukses";
        } catch (Exception $e) {
            return "$e";
        }
    }

    public function hapus_desa(Request $request)
    {
        $id = $request->id;

        try {
            $data = $request->validate([
                'id' => 'required',
            ]);
            $desa = Desa::findOrFail($id);
            $desa->delete();
            return "Sukses";
        } catch (Exception $e) {
            return "$e";
        }
    }


    public function get_kabupaten(Request $request)
    {
        $id = decrypt($request->id);
        $kab = Kabupaten::where('provinsi_id', '=', $id)->get();
        return response()->json($kab);
    }


    public function simpan_kabupaten(Request $request)
    {
        $id_pro=decrypt($request->id_pro);
        try {
            $data = $request->validate([
                'nama' => 'required',
                'id_pro'=>'required',
            ]);
            $kabupaten = new Kabupaten();
            $kabupaten->kabupaten = $request->nama;
            $kabupaten->provinsi_id = $id_pro;
            $kabupaten->save();
            return "Sukses";
        } catch (Exception $e) {
            return "$e";
        }
    }

    public function hapus_kabupaten(Request $request)
    {
        $id = $request->id;

        try {
            $data = $request->validate([
                'id' => 'required',
            ]);
            $kabupaten = Kabupaten::findOrFail($id);
            $kabupaten->delete();
            return "Sukses";
        } catch (Exception $e) {
            return "$e";
        }
    }
}
