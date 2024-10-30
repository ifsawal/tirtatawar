<?php

namespace App\Livewire;

use App\Models\Website\Layanan;
use Livewire\Component;

class DetilLayanan extends Component
{

    public $layanan;

    public function mount($id)
    {
        $this->layanan = Layanan::findOrFail($id);
    }
    public function render()
    {
        return view('livewire.detil-layanan', ['layanan'=> $this->layanan]);
    }
}
