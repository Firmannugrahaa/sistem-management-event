<?php

namespace App\Policies;

use App\Models\Guest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GuestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_guests');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Guest $guest): bool
    {
        if ($user->can('view_guests')) {
            return true;
        }

        if ($user->id === $guest->event->user_id) {
            return true;
        }

        if ($user->owner_id === $guest->event->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_guests');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Guest $guest): bool
    {
        if ($user->can('edit_guests')) {
            return true;
        }

        if ($user->id === $guest->event->user_id) {
            return true;
        }

        if ($user->owner_id === $guest->event->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Guest $guest): bool
    {
        if ($user->can('delete_guests')) {
            return true;
        }

        if ($user->id === $guest->event->user_id) {
            return true;
        }

        if ($user->owner_id === $guest->event->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Guest $guest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Guest $guest): bool
    {
        return false;
    }
}
