<?php

namespace App\Http\Controllers\Api\Pengguna;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function datauser()
    {
        $user_id = Auth::user()->id;
        $user = User::where('id', $user_id)->first();

        
        $pengguna = User::where('pdam_id', $user->pdam_id)
            ->get();

            return $user->getPermissionNames()
        // $role = $user->getRoleNames();
        // $col = collect($user->getAllPermissions());
        // return   $permisi = $col->map(function ($col) {
        //     return collect($col->toArray())
        //         ->only(['id', 'name'])
        //         ->all();
        // });
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
