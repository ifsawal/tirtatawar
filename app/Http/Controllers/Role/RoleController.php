<?php

namespace App\Http\Controllers\Role;

use App\Models\Role\Role;
use Illuminate\Http\Request;
use App\Models\Role\Permission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Role\Hakakses;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $role = Role::all();
        return view('role.role', compact('role'));
    }

    public function hakakses_role(Request $request, $id)
    {
        $role = Role::all();
        $permisi = Permission::all();
        
        $izin = Role::with('izin')->where('id', $id)->get();

        return view('role.role', compact('role', 'izin', 'permisi', 'id'));

        // $izin = DB::select('select 
        // p.id,
        // p.name, 
        // p.guard_name
        // from role_has_permissions rp, permissions p
        // where rp.permission_id=p.id &&
        // rp.role_id= ?', $id);
    }

    public function hapus_hakakses_role(Request $request, $idpermisi, $id_role)
    {
        $idpermisi = base64_decode($idpermisi);
        $id_role = base64_decode($id_role);
        DB::table('role_has_permissions')->where('permission_id', $idpermisi)->where('role_id', '=', $id_role)->delete();

        flash('Sukses terhapus.');
        return back();
    }

    public function tambah_hakakses(Request $request)
    {

        $jumlah = count($request->permisi);

        foreach ($request->permisi as $nilai) {
             DB::table('role_has_permissions')->insert(
                ['permission_id' => $nilai, 'role_id' => $request->id_role]
            );
        }

        flash('Sukses Tersimpan.');
        return back();
    }


    public function daftarizin()
    {

        // $role=Role::with('izin')
        // ->where('id','=',2)
        // ->get(['name','guard_name']);
        // return response()->json($role);
    }

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
            'role' => 'required',
            'jenis' => 'required',
        ]);


        $role = new Role();

        $role->name = $request->role;
        $role->guard_name = $request->jenis;
        $role->save();
        flash()->addSuccess('Sukses menambah role.');
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
        $role_e = Role::findOrFail($id);
        return view('role.role', compact('role_e'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $role =  Role::findOrFail($request->id);

        $role->name = $request->role;
        $role->guard_name = $request->jenis;
        $role->save();
        flash()->addSuccess('Sukses merubah role.');
        return redirect()->route('role');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        // flash('Berhasil Menghapus ...ok')->success();

        flash('Sukses terhapus.');
        return redirect()->route('role');
    }

    //
}
