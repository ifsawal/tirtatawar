<?php

namespace App\Models\Master;

use App\Models\User;
use App\Models\Master\Cabang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absen extends Model
{
    use HasFactory;

        public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'id');
    }



}
