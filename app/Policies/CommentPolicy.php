<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('comments_view');
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->hasPermission('comments_moderate');
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->hasPermission('comments_delete');
    }
}
