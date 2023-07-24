<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role\Model_has_role;
use App\Models\Role\Role;
use App\Models\User;
use Illuminate\Http\Request;

class HakaksesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return view('role.hakakses', compact('user'));
    }

    public function detil_hakakses($id)
    {
        $user = User::all();
        $user_role = Model_has_role::with('role')->where('model_id', $id)->get();
        if (count($user_role) == 0) {
            $role = Role::all();
        } else {
            $role = "";
        }
        return view('role.hakakses', compact('user', 'user_role', 'id', 'role'));
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
        $user=User::findOrFail($request->id_user);

        $user->assignRole($request->role);

        flash()->addSuccess('Sukses menambah role...');
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
