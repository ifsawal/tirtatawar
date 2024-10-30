<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function izin()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }
}
