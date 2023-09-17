<?php

namespace App\Models\Keluhan;

use App\Models\Keluhan\Keluhan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proses extends Model
{
    use HasFactory;

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class, 'keluhan_id', 'id');
    }
}
