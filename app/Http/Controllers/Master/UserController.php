<?php

namespace App\Http\Controllers\Master;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Master\Pdam;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        $pdam = Pdam::all();
        return view('master.user', compact('user', 'pdam'));
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
        $data = $request->validate([
            'user' => 'required',
            'email' => 'required|email',
            'pdam' => 'required',
        ]);
        $pdam_id = decrypt($request->pdam);

        $user = new User();

        $user->nama = $request->user;
        $user->email = $request->email;
        $user->pdam_id = $pdam_id;
        $user->j_permisi = 0;
        $user->password = bcrypt(123456);
        $user->save();
        flash()->addSuccess('Sukses menambah User.');
        return back();
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
        $id = decrypt($id);
        $pdam = Pdam::all();
        $user_e = User::findOrFail($id);
        $pdam_terpilih = Pdam::where('id', '=', $user_e->pdam_id)->get(['id', 'pdam']);

        return view('master.user', compact('user_e', 'pdam', 'pdam_terpilih'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {


        $id = decrypt($request->id);
        $pdam_id = decrypt($request->pdam);
        $user =  User::findOrFail($id);

        $user->nama = $request->user;
        $user->email = $request->email;
        $user->pdam_id = $pdam_id;
        $user->save();
        flash()->addSuccess('Sukses merubah User.');
        return redirect()->route('user');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = decrypt($id);
        $user = User::findOrFail($id);
        $user->delete();
        // flash('Berhasil Menghapus ...ok')->success();

        flash('Sukses terhapus.');
        return redirect()->route('user');
    }
}
