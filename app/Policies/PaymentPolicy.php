<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_payments');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payment $payment): bool
    {
        if ($user->can('view_payments')) {
            return true;
        }

        if ($user->id === $payment->invoice->event->user_id) {
            return true;
        }

        if ($user->owner_id === $payment->invoice->event->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_payments');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payment $payment): bool
    {
        if ($user->can('edit_payments')) {
            return true;
        }

        if ($user->id === $payment->invoice->event->user_id) {
            return true;
        }

        if ($user->owner_id === $payment->invoice->event->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payment $payment): bool
    {
        if ($user->can('delete_payments')) {
            return true;
        }

        if ($user->id === $payment->invoice->event->user_id) {
            return true;
        }

        if ($user->owner_id === $payment->invoice->event->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Payment $payment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Payment $payment): bool
    {
        return false;
    }
}
