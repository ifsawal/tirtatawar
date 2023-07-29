<?php

namespace App\Http\Controllers\Api\Master;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\Master\UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function ganti_p(Request $request)
    {
        // auth()->user()->tokens()->delete();

        $user = Auth::user();
        $user = User::findOrFail($user->id);
        $user->password = bcrypt($request->password);
        $user->save();
        return response()
            ->json([
                'sukses' => true,
                'pesan' => "Perubahan berhasil...",
            ], 202);
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'nama' => 'required|min:4',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'j_permisi' => 0,
        ]);


        return response()->json([
            'sukses' => true,
            'pesan' => "Perubahan berhasil...",
        ], 201);
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
        // $user = User::with('pdam:id,pdam')->findOrFail($id);  // dengan relasa
        $user = User::findOrFail($id); //tanpa relasi
        // $user = User::all();
        return response()->json([
            'sukses' => true,
            'pesan' => "ditemukan",
            'data' => new UserResource($user),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:4',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'sukses' => 0,
                'pesan' => $validator->errors(),
            ]);
        }

        $user = User::findOrFail($id);
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json([
            'sukses' => true,
            'pesan' => "Perubahan berhasil...",
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'sukses' => 1,
            'pesan' => 'Sukses Terhapus...',
        ], 204);
    }
}
