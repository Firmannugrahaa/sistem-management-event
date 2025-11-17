<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PendingApprovalComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $pendingApprovalCount = 0;
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->can('user.approve') || $user->can('vendor.approve')) {
                $pendingApprovalCount = User::where('status', 'pending')->count();
            }
        }

        $view->with('pendingApprovalCount', $pendingApprovalCount);
    }
}
