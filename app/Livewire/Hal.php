<?php

namespace App\Livewire;

use App\Models\Website\Halaman;
use Livewire\Component;

class Hal extends Component
{

    public $slug = null;

    public function mount($slug)
    {
        $this->slug = $slug;
    }



    public function render()
    {
        $hal = Halaman::where('slug', $this->slug)->first();
        if ($hal == null) {
            abort(404);
        }
        return view(
            'livewire.hal',
            ['hal'=>$hal]
        );
    }
}
