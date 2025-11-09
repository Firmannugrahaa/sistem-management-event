<?php

namespace App\Http\Controllers\SuperUser;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Venue;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get system statistics
            $usersCount = User::count();
            $eventsCount = Event::count();
            $venuesCount = Venue::count();
            $vendorsCount = Vendor::count();
            
            // Get recent users
            $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
            
            // Get system information
            $systemInfo = [
                'laravel_version' => app()->version(),
                'php_version' => PHP_VERSION,
                'database' => config('database.default'),
                'environment' => app()->environment(),
            ];
            
            return view('superuser.dashboard', compact('usersCount', 'eventsCount', 'venuesCount', 'vendorsCount', 'recentUsers', 'systemInfo'));
        } catch (\Exception $e) {
            Log::error('Error in SuperUser Dashboard: ' . $e->getMessage());
            // Return a simple error page for debugging
            return response()->view('errors.500', ['exception' => $e], 500);
        }
    }
}