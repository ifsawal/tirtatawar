<?php

namespace App\Policies;

use App\Models\User;

use App\Models\Admin;
use App\Models\Website\Anggota;
use Illuminate\Auth\Access\Response;

class AnggotaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $user): bool
    {
        return true;
        // return $user->hasPermissionTo('tambah_anggota','web');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $user, Anggota $anggota): bool
    {
        return true;
        // return $user->hasAnyPermission('tambah_anggota','web');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $user): bool
    {
        return true;
        // return $user->hasAnyPermission('tambah_anggota','web');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $user, Anggota $anggota): bool
    {
        return true;
        // return $user->hasAnyPermission('tambah_anggota','web');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $user, Anggota $anggota): bool
    {
        return true;
        // return $user->hasAnyPermission('tambah_anggota','web');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $user, Anggota $anggota): bool
    {
        return true;
        // return $user->hasAnyPermission('tambah_anggota','web');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $user, Anggota $anggota): bool
    {
        return true;
        // return $user->hasAnyPermission('tambah_anggota','web');
    }
}
