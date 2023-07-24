<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hakakses extends Model
{
    use HasFactory;

    protected $table = 'role_has_permissions';

    // public function izin()
    // {
    //     return $this->hasMany(Permission::class,'permission_id','id');
    // }

    
}
