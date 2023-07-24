<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public function izin()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }



}
