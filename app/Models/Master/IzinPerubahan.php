<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinPerubahan extends Model
{
    use HasFactory;

    public function user_penyetuju()
    {
        return $this->belongsTo(User::class, 'user_id_penyetuju', 'id');
    }
}
