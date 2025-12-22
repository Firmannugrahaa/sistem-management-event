<?php

namespace App\Policies;

use App\Models\ClientRequest;
use App\Models\User;

class ClientRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['SuperUser', 'Owner', 'Admin', 'Staff', 'Vendor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClientRequest $clientRequest): bool
    {
        // SuperUser, Owner, Admin can view all
        if ($user->hasAnyRole(['SuperUser', 'Owner', 'Admin'])) {
            return true;
        }

        // Staff can only view assigned requests
        if ($user->hasRole('Staff')) {
            return $clientRequest->assigned_to === $user->id;
        }

        // Vendor can only view their requests
        if ($user->hasRole('Vendor')) {
            $vendor = $user->vendor;
            return $vendor && $clientRequest->vendor_id === $vendor->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['SuperUser', 'Owner', 'Admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClientRequest $clientRequest): bool
    {
        // SuperUser, Owner, Admin can update all
        if ($user->hasAnyRole(['SuperUser', 'Owner', 'Admin'])) {
            return true;
        }

        // Staff can update assigned requests (status only)
        if ($user->hasRole('Staff')) {
            return $clientRequest->assigned_to === $user->id;
        }

        // Vendor can update their requests (limited fields)
        if ($user->hasRole('Vendor')) {
            $vendor = $user->vendor;
            return $vendor && $clientRequest->vendor_id === $vendor->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClientRequest $clientRequest): bool
    {
        // SuperUser, Owner, Admin can delete
        return $user->hasAnyRole(['SuperUser', 'Owner', 'Admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClientRequest $clientRequest): bool
    {
        // Only SuperUser can restore
        return $user->hasRole('SuperUser');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClientRequest $clientRequest): bool
    {
        // Only SuperUser can force delete
        return $user->hasRole('SuperUser');
    }
}
