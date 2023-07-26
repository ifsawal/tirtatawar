<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Pdam;
use Illuminate\Http\Request;

class PdamController extends Controller
{
    public function index()
    {
        $pdam = Pdam::all();
        return view('master.pdam', compact('pdam'));
    }


    public function tambah_pdam(Request $request)
    {

        $data = $request->validate([
            'pdam' => 'required',
        ]);


        $role = new Pdam();

        $role->pdam = $request->pdam;
        $role->save();
        flash()->addSuccess('Sukses menambah role.');
        return back();
    }


    public function edit(string $id)
    {
        $id = decrypt($id);
        $pdam_e = Pdam::findOrFail($id);
        return view('master.pdam', compact('pdam_e'));
    }

    public function update(Request $request)
    {
        $id = decrypt($request->id);
        $pdam =  Pdam::findOrFail($id);

        $pdam->pdam = $request->pdam;
        $pdam->save();
        flash()->addSuccess('Sukses merubah pdam.');
        return redirect()->route('pdam');
    }

    public function destroy(string $id)
    {
        $id = decrypt($id);
        $pdam = Pdam::findOrFail($id);
        $pdam->delete();
        // flash('Berhasil Menghapus ...ok')->success();

        flash('Sukses terhapus.');
        return redirect()->route('pdam');
    }
}
