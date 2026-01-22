<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the list of users.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view a specific user.
     */
    public function view(User $authUser, User $user): bool
    {
        return $authUser->role === 'admin';
    }

    /**
     * Determine whether the user can update a user.
     */
    public function update(User $authUser, User $user): bool
    {
        // Only admins can update users
        return $authUser->role === 'admin';
    }

    /**
     * Determine whether the user can delete a user.
     */
    public function delete(User $authUser, User $user): bool
    {
        // Only admins can delete users, and can't delete themselves
        return $authUser->role === 'admin' && $authUser->id !== $user->id;
    }
}