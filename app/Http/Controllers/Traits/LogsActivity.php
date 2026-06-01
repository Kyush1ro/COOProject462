<?php

namespace App\Http\Controllers\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Records an audit log entry for a specific action.
     *
     * @param string $action 'created', 'updated', 'deleted'
     * @param object|null $model The model instance that was affected.
     * @param array $oldData
     */
    protected function recordLog(string $action, $model = null, array $oldData = [])
    {
        // 1. Security Check: Ensure a user is logged in
        if (!Auth::check()) {
            return;
        }

        // Determine the ID of the affected model (use custom Academic_ID or default id)
        $modelId = null;
        if ($model) {
            // Use Academic_ID if the model has it (e.g., User), otherwise use 'id'
            $modelId = $model->Academic_ID ?? $model->id;
        }

        // 2. Insert the log record into the database
        \App\Models\AuditLog::create([
            'user_id' => Auth::user()->Academic_ID,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $modelId,
            'old_values' => $oldData,
            // For 'created', getChanges() is empty because the model is fresh. Use toArray() instead.
            // For 'updated', getChanges() works but only if called immediately after update.
            'new_values' => ($action === 'created' && $model) ? $model->toArray() : ($model ? $model->getChanges() : null),
            'ip_address' => Request::ip(),
        ]);
    }
}
