<?php
namespace App\Services;
use App\Models\{User, AuditLog};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public function log(
        ?User  $actor,
        string $event,
        ?Model $subject = null,
        array  $extra   = [],
    ): AuditLog {
        $old = $subject?->getOriginal() ?? [];
        $new = $subject?->getDirty()    ?? [];

        return AuditLog::create([
            'user_id'         => $actor?->id,
            'organization_id' => $actor?->organization_id,
            'auditable_type'  => $subject ? get_class($subject) : null,
            'auditable_id'    => $subject?->id,
            'event'           => $event,
            'old_values'      => array_merge($old, $extra['old'] ?? []),
            'new_values'      => array_merge($new, $extra['new'] ?? []),
            'ip_address'      => Request::ip(),
            'user_agent'      => Request::userAgent(),
            'tags'            => $extra['tags'] ?? null,
        ]);
    }

    public function logCustom(array $data): AuditLog
    {
        return AuditLog::create(array_merge($data, [
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]));
    }
}
