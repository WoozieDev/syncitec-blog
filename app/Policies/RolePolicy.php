<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('roles_view');
    }

    /**
     * Determine whether the user can view the model_
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('roles_view');
    }

    /**
     * Determine whether the user can update the model_
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasPermission('roles_edit');
    }
}
