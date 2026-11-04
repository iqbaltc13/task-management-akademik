<?php

namespace App\Policies;

use App\Models\MasterSubUnit;
use App\Models\User;

class MasterSubUnitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view master sub units');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create master sub unit');
    }

    public function update(User $user, MasterSubUnit $model): bool
    {
        return $user->hasPermissionTo('edit master sub unit');
    }

    public function delete(User $user, MasterSubUnit $model): bool
    {
        return $user->hasPermissionTo('archive master sub unit');
    }

    public function restore(User $user, MasterSubUnit $model): bool
    {
        return $user->hasPermissionTo('restore master sub unit');
    }
}
