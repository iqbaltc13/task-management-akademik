<?php

namespace App\Policies;

use App\Models\MasterSto;
use App\Models\User;

class MasterStoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view master stos');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create master sto');
    }

    public function update(User $user, MasterSto $model): bool
    {
        return $user->hasPermissionTo('edit master sto');
    }

    public function delete(User $user, MasterSto $model): bool
    {
        return $user->hasPermissionTo('archive master sto');
    }

    public function restore(User $user, MasterSto $model): bool
    {
        return $user->hasPermissionTo('restore master sto');
    }
}
