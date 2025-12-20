<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('tags_view');
    }

    /**
     * Determine whether the user can view the model_
     */
    public function view(User $user, Tag $tag): bool
    {
        return $user->hasPermission('tags_view');
    }

    /**
     * Determine whether the user can create models_
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('tags_create');
    }

    /**
     * Determine whether the user can update the model_
     */
    public function update(User $user, Tag $tag): bool
    {
        return $user->hasPermission('tags_update');
    }

    /**
     * Determine whether the user can delete the model_
     */
    public function delete(User $user, Tag $tag): bool
    {
        return $user->hasPermission('tags_delete');
    }

    /**
     * Determine whether the user can restore the model_
     */
    public function restore(User $user, Tag $tag): bool
    {
        return $user->hasPermission('tags_restore');
    }
    
}
