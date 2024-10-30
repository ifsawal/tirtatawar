<?php

namespace App\Livewire;

use App\Models\Website\Layanan;
use Livewire\Component;

class Beranda extends Component
{
    public function render()
    {
        $layanan=Layanan::orderBy('judul','ASC')->get();
        return view('livewire.beranda',['layanan'=>$layanan]);
    }
}
