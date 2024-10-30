<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Website\Layanan;

class HalamanLayanan extends Component
{
    public function render()
    {
        $layanan=Layanan::orderBy('judul','ASC')->get();
        return view('livewire.halaman-layanan',['layanan'=>$layanan]);
    }
}
