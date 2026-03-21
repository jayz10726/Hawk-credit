<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class AuditLog extends Model
{
    // Immutable — never allow updates
    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id','organization_id','auditable_type','auditable_id',
        'event','old_values','new_values','ip_address','user_agent','tags',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'tags'       => 'array',
    ];

    public function actor(): BelongsTo
    { return $this->belongsTo(User::class, 'user_id'); }

    public function auditable(): MorphTo
    { return $this->morphTo(); }

   public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Prevent any updates to audit records
    protected static function booted(): void
    {
        static::updating(fn() => false);  // silently block all updates
    }
}

