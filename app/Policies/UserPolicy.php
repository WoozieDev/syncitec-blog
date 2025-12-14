<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view_users') || $user->hasPermission('manage_users');
    }

    public function view(User $user, User $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage_users');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermission('manage_users');
    }

    public function delete(User $user, User $model): bool
    {
        // evita que alguien se borre a sí mismo por accidente, opcional
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermission('manage_users');
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasPermission('manage_users');
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false; // no usamos forceDelete en MVP
    }
}
