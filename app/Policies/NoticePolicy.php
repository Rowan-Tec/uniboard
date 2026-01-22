<?php

namespace App\Policies;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NoticePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any notices (approval list).
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view a specific notice.
     */
    public function view(User $user, Notice $notice): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can create notices.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Determine whether the user can update (edit) the notice.
     */
    public function update(User $user, Notice $notice): bool
    {
        return $user->role === 'admin';
    }

   /**
 * Determine whether the user can delete (soft-delete) the notice.
 */
public function delete(User $user, Notice $notice): bool
{
    // User can delete their own notice (soft-delete to trash)
    // OR admin can delete any notice
    return $user->id === $notice->user_id || $user->role === 'admin';
}

    /**
     * Determine whether the user can approve the notice.
     */
    public function approve(User $user, Notice $notice): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can reject the notice.
     */
    public function reject(User $user, Notice $notice): bool
    {
        return $user->role === 'admin';
    }
    
}