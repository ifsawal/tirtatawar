<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Models\Master\Client2;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthClient2Controller extends Controller
{
    public function daftar(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'id' => 'required',
            'nama' => 'required',
            'email' => 'required|string|email|max:255|unique:clients',

        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 205);
        }

        $client = new Client2();
        $client->id = $r->id;
        $client->nama = $r->nama;
        $client->email = $r->email;
        $client->password = bcrypt($r->id);
        $client->save();




        return response()->json([
            'sukses' => true,
            'pesan' => "Pendaftaran berhasil...",
            'data' => $client,
        ], 201);
    }

    public function login(Request $r)
    {
        if (!Auth::guard('client2')->attempt($r->only('email', 'password'))) {
            return response()
                ->json(['sukses' => false, 'pesan' => 'Login gagal...'], 401);
        }

        $client = Client2::where('email', $r['email'])->firstOrFail();

        return $token = $client->createToken('auth_token', ['client2'])->plainTextToken;
    }
}
