<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AuditLogController extends Controller
{
    /**
     * Display listing of audit logs with filters.
     */
    public function index(Request $request)
    {
        // Only admins can view audit logs
        Gate::authorize('is-admin');

        $query = AuditLog::with('user')->latest('created_at');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model
        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50)->withQueryString();
        
        $users = User::orderBy('name')->get();
        $actions = AuditLog::select('action')->distinct()->pluck('action');

        return view('audit-logs.index', compact('logs', 'users', 'actions'));
    }

    /**
     * Show detailed view of a single audit log.
     */
    public function show(AuditLog $auditLog)
    {
        Gate::authorize('is-admin');
        
        $auditLog->load('user');
        
        return view('audit-logs.show', compact('auditLog'));
    }

    /**
     * Export audit logs to Excel.
     */
    public function export(Request $request)
    {
        Gate::authorize('is-admin');
        
        // Using simple CSV export (no Laravel Excel needed)
        $query = AuditLog::with('user')->latest('created_at');

        // Apply same filters as index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('model')) {
            $query->where('model', 'like', '%' . $request->model . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->limit(5000)->get(); // Limit untuk prevent memory issues

        $filename = 'audit_logs_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['ID', 'User', 'Action', 'Model', 'Record ID', 'IP Address', 'Timestamp']);
            
            // Data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : '-',
                    $log->getActionLabel(),
                    $log->getModelName(),
                    $log->model_id ?? '-',
                    $log->ip_address ?? '-',
                    $log->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
