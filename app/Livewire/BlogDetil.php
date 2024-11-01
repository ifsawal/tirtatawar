<?php

namespace App\Livewire;

use App\Models\Website\Artikel;
use Livewire\Component;

class BlogDetil extends Component
{
    public $id=null;

    public function mount($id){
        $this->id= $id;
    }
    public function render()
    {

        $blog=Artikel::select('artikels.*','katagoris.nama','admins.name as nama_admin')->join('katagoris','katagoris.id','artikels.katagori_id')
        ->join('admins','admins.id','artikels.admin_id')
        ->where('artikels.slug',$this->id)->first();
        return view('livewire.blog-detil',
    [
        'artikel'=>$blog
    ]
    );
    }
}
