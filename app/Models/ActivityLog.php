<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $fillable = ['user_id', 'organization_id', 'entity_type', 'entity_id', 'action', 'description', 'changes', 'ip_address'];
    protected $casts = ['changes' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $action, string $description, string $entityType = null, int $entityId = null, array $changes = [])
    {
        $user = auth()->user();
        return static::create([
            'user_id' => $user?->id,
            'organization_id' => $user?->organization_id,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action' => $action,
            'description' => $description,
            'changes' => $changes ?: null,
            'ip_address' => request()->ip(),
        ]);
    }
}
