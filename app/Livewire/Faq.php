<?php

namespace App\Livewire;

use App\Models\Website\Faq as WebsiteFaq;
use Livewire\Component;

class Faq extends Component
{
    public function render()
    {
        $tanya = WebsiteFaq::orderBy('id', 'DESC')->get();
        return view(
            'livewire.faq',
            [
                'tanya' => $tanya
            ]
        );
    }
}
