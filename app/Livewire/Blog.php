<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Website\Artikel;
use App\Models\Website\Katagori;

class Blog extends Component
{

    #[Url]
    public $katagori_url = null;
    public $halaman = 7;


    public function render()
    {
        $this->katagori_url;
        $katagori = Katagori::all();
        if (!empty($this->katagori_url)) {
            $cek_katagori = Katagori::where('slug',$this->katagori_url)->first();
            if(empty($cek_katagori)){abort(404);}
            $artikel = Artikel::where('katagori_id', $cek_katagori->id)
            ->where('status',1)
            ->orderBy('id', "DESC")
            ->paginate($this->halaman);
        } else {
            $artikel = Artikel::orderBy('id', "DESC")->where('status',1)->paginate($this->halaman);
        }

        $artikelterakhir = Artikel::orderBy('id', "DESC")->where('status',1)->get()->take(3);

        return view('livewire.blog', [
            "artikel" => $artikel,
            "katagori" => $katagori,
            "artikelterakhir" => $artikelterakhir
        ]);
    }
}
