<?php

namespace App\Http\Controllers;

use App\Models\ClientRequest;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TrashController extends Controller
{
    /**
     * Ensure only SuperUser can access
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Security: Only SuperUser can access trash management
            abort_unless(auth()->check() && auth()->user()->hasRole('SuperUser'), 403, 'Unauthorized. SuperUser access only.');
            
            return $next($request);
        });
    }

    /**
     * Show trash overview dashboard
     */
    public function index()
    {
        $stats = [
            'client_requests' => ClientRequest::onlyTrashed()->count(),
            'events' => Event::onlyTrashed()->count(),
            'invoices' => Invoice::onlyTrashed()->count(),
        ];

        // Recent deletions (last 7 days)
        $recentDeletions = collect([
            ...ClientRequest::onlyTrashed()
                ->where('deleted_at', '>=', now()->subDays(7))
                ->with('deletedBy')
                ->get()
                ->map(fn($item) => [
                    'type' => 'Client Request',
                    'id' => $item->id,
                    'name' => $item->client_name,
                    'deleted_at' => $item->deleted_at,
                    'deleted_by' => $item->deletedBy?->name,
                ]),
        ])->sortByDesc('deleted_at')->take(10);

        return view('admin.trash.index', compact('stats', 'recentDeletions'));
    }

    /**
     * Show deleted client requests
     */
    public function clientRequests()
    {
        $trashedRequests = ClientRequest::onlyTrashed()
            ->with(['assignee', 'vendor', 'deletedBy'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);

        return view('admin.trash.client-requests', compact('trashedRequests'));
    }

    /**
     * Restore a client request
     */
    public function restoreClientRequest($id)
    {
        // Security: Validate ID is numeric
        if (!is_numeric($id)) {
            abort(400, 'Invalid request ID');
        }

        // Use DB transaction for data integrity
        DB::beginTransaction();
        
        try {
            $clientRequest = ClientRequest::onlyTrashed()->findOrFail($id);
            
            // Security check: Ensure user has permission
            Gate::authorize('restore', $clientRequest);
            
            // Restore the request
            $clientRequest->restore();
            
            // Clear deleted_by field
            $clientRequest->update(['deleted_by' => null]);
            
            // Log activity for audit trail
            ActivityLog::log(
                'restored',
                $clientRequest,
                "Client request #{$clientRequest->id} restored by " . auth()->user()->name,
                [
                    'client_name' => $clientRequest->client_name,
                    'previous_status' => $clientRequest->status,
                    'restored_at' => now()->toDateTimeString(),
                ]
            );
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Client request restored successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            \Log::error('Failed to restore client request', [
                'id' => $id,
                'error' => $e->getMessage(),
                'user' => auth()->id(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to restore client request. Please try again.');
        }
    }

    /**
     * Force delete (permanent) a client request
     */
    public function forceDeleteClientRequest($id)
    {
        // Security: Validate ID
        if (!is_numeric($id)) {
            abort(400, 'Invalid request ID');
        }

        DB::beginTransaction();
        
        try {
            $clientRequest = ClientRequest::onlyTrashed()->findOrFail($id);
            
            // Security: Additional confirmation
            Gate::authorize('forceDelete', $clientRequest);
            
            // Backup data before permanent delete (for audit)
            $backupData = $clientRequest->toArray();
            
            // Log BEFORE deleting
            ActivityLog::create([
                'user_id' => auth()->id(),
                'subject_type' => 'ClientRequest',
                'subject_id' => $clientRequest->id,
                'action' => 'force_deleted',
                'description' => "Client request #{$clientRequest->id} permanently deleted by " . auth()->user()->name,
                'metadata' => [
                    'backup_data' => $backupData,
                    'reason' => 'Permanent deletion from trash',
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            
            // Permanent delete
            $clientRequest->forceDelete();
            
            DB::commit();
            
            return redirect()->back()
                ->with('warning', 'Client request permanently deleted. This action cannot be undone!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to force delete client request', [
                'id' => $id,
                'error' => $e->getMessage(),
                'user' => auth()->id(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to delete client request. Please try again.');
        }
    }

    /**
     * Bulk restore multiple items
     */
    public function restoreBulk(Request $request)
    {
        // Security: Validate input
        $validated = $request->validate([
            'ids' => 'required|array|max:100', // Limit to 100 items at once
            'ids.*' => 'required|numeric',
            'model' => 'required|in:client_requests,events,invoices'
        ]);

        $modelClass = match($validated['model']) {
            'client_requests' => ClientRequest::class,
            'events' => Event::class,
            'invoices' => Invoice::class,
        };

        DB::beginTransaction();
        
        try {
            // Security: Sanitize IDs (already validated as numeric)
            $ids = array_map('intval', $validated['ids']);
            
            $restored = $modelClass::onlyTrashed()
                ->whereIn('id', $ids)
                ->get();
            
            $count = 0;
            foreach ($restored as $item) {
                $item->restore();
                $item->update(['deleted_by' => null]);
                
                // Log each restoration
                ActivityLog::log(
                    'bulk_restored',
                    $item,
                    "Bulk restored by " . auth()->user()->name
                );
                
                $count++;
            }
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', "{$count} items restored successfully!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Failed to bulk restore', [
                'model' => $validated['model'],
                'count' => count($validated['ids']),
                'error' => $e->getMessage(),
                'user' => auth()->id(),
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to restore items. Please try again.');
        }
    }

    /**
     * Auto-cleanup old trash (can be called via cron)
     */
    public function autoCleanup(Request $request)
    {
        // Security: Only allow via CLI or with secret token
        if (!app()->runningInConsole()) {
            $token = $request->header('X-Cleanup-Token');
            if ($token !== config('app.cleanup_token')) {
                abort(403, 'Unauthorized');
            }
        }

        $days = config('trash.auto_cleanup_days', 90);
        $threshold = now()->subDays($days);

        $deleted = ClientRequest::onlyTrashed()
            ->where('deleted_at', '<', $threshold)
            ->forceDelete();

        ActivityLog::create([
            'user_id' => null,
            'subject_type' => 'System',
            'subject_id' => 0,
            'action' => 'auto_cleanup',
            'description' => "Auto-cleanup deleted {$deleted} client requests older than {$days} days",
            'metadata' => [
                'threshold_date' => $threshold->toDateTimeString(),
                'deleted_count' => $deleted,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => 'System/Auto-Cleanup',
        ]);

        return response()->json([
            'success' => true,
            'deleted' => $deleted,
            'message' => "Cleaned up {$deleted} old records"
        ]);
    }
}
