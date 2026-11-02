<?php

namespace App\Policies;

use App\Models\MasterUnit;
use App\Models\User;

class MasterUnitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view master units');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create master unit');
    }

    public function update(User $user, MasterUnit $model): bool
    {
        return $user->hasPermissionTo('edit master unit');
    }

    public function delete(User $user, MasterUnit $model): bool
    {
        return $user->hasPermissionTo('archive master unit');
    }

    public function restore(User $user, MasterUnit $model): bool
    {
        return $user->hasPermissionTo('restore master unit');
    }
}