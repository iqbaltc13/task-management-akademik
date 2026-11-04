<?php

namespace App\Policies;

use App\Models\MasterJabatan;
use App\Models\User;

class MasterJabatanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view master jabatans');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create master jabatan');
    }

    public function update(User $user, MasterJabatan $model): bool
    {
        return $user->hasPermissionTo('edit master jabatan');
    }

    public function delete(User $user, MasterJabatan $model): bool
    {
        return $user->hasPermissionTo('archive master jabatan');
    }

    public function restore(User $user, MasterJabatan $model): bool
    {
        return $user->hasPermissionTo('restore master jabatan');
    }
}
