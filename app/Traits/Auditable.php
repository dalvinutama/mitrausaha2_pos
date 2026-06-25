<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAudit('CREATE', $model, null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $original = $model->getOriginal();
            $changes = $model->getChanges();
            
            // Log only changed values
            if (!empty($changes)) {
                $oldValues = array_intersect_key($original, $changes);
                self::logAudit('UPDATE', $model, $oldValues, $changes);
            }
        });

        static::deleted(function ($model) {
            self::logAudit('DELETE', $model, $model->getAttributes(), null);
        });
    }

    protected static function logAudit($action, $model, $oldValues, $newValues)
    {
        $userId = Auth::id();
        $module = class_basename($model);
        
        // Coba untuk mendapatkan nama jika memungkinkan
        $nameField = $model->name ?? $model->title ?? $model->nama ?? '';
        $identifier = $nameField ? " ($nameField)" : "";
        
        $description = "{$action} {$module} ID: " . $model->getKey() . $identifier;

        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'record_id' => $model->getKey(),
        ]);
    }
}
