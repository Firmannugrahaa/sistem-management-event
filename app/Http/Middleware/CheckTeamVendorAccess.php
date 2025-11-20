<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CheckTeamVendorAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user has permission to access team or vendor management
        $hasTeamPermission = Gate::allows('team_member.read') || Gate::allows('team_member.create') ||
                            Gate::allows('team_member.update') || Gate::allows('team_member.delete') ||
                            Gate::allows('team_member.approve');

        $hasVendorPermission = Gate::allows('vendor.read') || Gate::allows('vendor.create') ||
                              Gate::allows('vendor.update') || Gate::allows('vendor.delete') ||
                              Gate::allows('vendor.approve');

        if (!$hasTeamPermission && !$hasVendorPermission) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
