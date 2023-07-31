<?php

namespace App\Models\Master;

use App\Models\Master\Rute;
use App\Models\Master\Kabupaten;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pdam extends Model
{
    use HasFactory;

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten:id', 'id');
    }

    public function rute()
    {
        return $this->hasMany(Rute::class, 'pdam_id', 'id');
    }
}
