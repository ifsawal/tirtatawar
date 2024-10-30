<?php

namespace App\Livewire;

use App\Models\Website\Anggota;
use Livewire\Component;

class HalamanAnggota extends Component
{
    public function render()
    {
        $anggota=Anggota::orderBy('nama','ASC')->get();

        return view('livewire.halaman-anggota',['anggota'=>$anggota]);
    }
}
