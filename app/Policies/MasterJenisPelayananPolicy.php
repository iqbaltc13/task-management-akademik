<?php

namespace App\Policies;

use App\Models\MasterJenisPelayanan;
use App\Models\User;

class MasterJenisPelayananPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view jenis pelayanans');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create jenis pelayanan');
    }

    public function update(User $user, MasterJenisPelayanan $jenisPelayanan): bool
    {
        return $user->hasPermissionTo('edit jenis pelayanan');
    }

    public function delete(User $user, MasterJenisPelayanan $jenisPelayanan): bool
    {
        return $user->hasPermissionTo('archive jenis pelayanan');
    }

    public function restore(User $user, MasterJenisPelayanan $jenisPelayanan): bool
    {
        return $user->hasPermissionTo('restore jenis pelayanan');
    }
}
