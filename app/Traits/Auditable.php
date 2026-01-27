<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait Auditable
{
    /**
     * Boot the auditable trait for a model.
     */
    public static function bootAuditable(): void
    {
        // Log when a model is created
        static::created(function ($model) {
            self::logAudit('created', $model, [
                'new' => $model->getAuditableAttributes(),
            ]);
        });

        // Log when a model is updated
        static::updated(function ($model) {
            $changes = $model->getChanges();
            
            // Don't log if only updated_at changed
            if (count($changes) === 1 && isset($changes['updated_at'])) {
                return;
            }

            self::logAudit('updated', $model, [
                'old' => array_intersect_key($model->getOriginal(), $changes),
                'new' => $changes,
            ]);
        });

        // Log when a model is deleted
        static::deleted(function ($model) {
            self::logAudit('deleted', $model, [
                'old' => $model->getAuditableAttributes(),
            ]);
        });
    }

    /**
     * Create an audit log entry.
     */
    protected static function logAudit(string $action, $model, array $details = []): void
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model' => get_class($model),
                'model_id' => $model->id ?? null,
                'details' => $details,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the app if logging fails
            \Log::warning('Audit log failed: ' . $e->getMessage());
        }
    }

    /**
     * Get attributes that should be audited.
     * Override this method in models to customize which attributes are logged.
     */
    public function getAuditableAttributes(): array
    {
        // Exclude sensitive/irrelevant fields
        $excluded = [
            'password',
            'remember_token',
            'created_at',
            'updated_at',
            'email_verified_at',
        ];

        return collect($this->getAttributes())
            ->except($excluded)
            ->toArray();
    }
}
