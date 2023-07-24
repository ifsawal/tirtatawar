<?php

namespace App\Http\Controllers\Role;

use Illuminate\Http\Request;
use App\Models\Role\Permission;
use App\Http\Controllers\Controller;

class IzinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $izin=Permission::orderBy('id','desc')->paginate(10);
        return view('role.izin', compact('izin'));
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
            'izin' => 'required',
            'jenis' => 'required',
        ]);

 
        $izin = new Permission();

        $izin->name = $request->izin;
        $izin->guard_name = $request->jenis;
        $izin->save();
        flash()->addSuccess('Sukses menambah jenis izin.');
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
        $izin_e = Permission::findOrFail($id);
        return view('role.izin', compact('izin_e'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        
            $izin =  Permission::findOrFail($request->id);
    
            $izin->name = $request->izin;
            $izin->guard_name = $request->jenis;
            $izin->save();
            flash()->addSuccess('Sukses merubah jenis izin.');
            return redirect()->route('izin');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $izin=Permission::findOrFail($id);
        $izin->delete();
        
        flash('Sukses terhapus.');
        return redirect()->route('izin');
    }
}
