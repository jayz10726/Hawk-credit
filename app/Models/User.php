<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo,HasMany,HasOne,MorphMany};
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'uuid','organization_id','first_name','last_name','email','phone',
        'national_id','date_of_birth','employment_status','monthly_income',
        'password','profile_photo_path','is_active','two_factor_enabled',
    ];

    protected $hidden = ['password','remember_token','two_factor_secret'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'date_of_birth'     => 'date',
        'is_active'         => 'boolean',
        'monthly_income'    => 'decimal:2',
    ];

    // ── Relationships ───────────────────────────────────────────
    public function organization(): BelongsTo
    { return $this->belongsTo(Organization::class); }

    public function creditScore(): HasOne
    { return $this->hasOne(CreditScore::class); }

    public function creditRequests(): HasMany
    { return $this->hasMany(CreditRequest::class); }

    public function loans(): HasMany
    { return $this->hasMany(Loan::class); }

    public function repayments(): HasMany
    { return $this->hasMany(Repayment::class); }

    public function auditLogs(): MorphMany
    { return $this->morphMany(AuditLog::class, 'auditable'); }

    // ── Scopes ─────────────────────────────────────────────────
    public function scopeForOrganization($q, $orgId)
    { return $q->where('organization_id', $orgId); }

    public function scopeActive($q)
    { return $q->where('is_active', true); }

    // ── Accessors ───────────────────────────────────────────────
    public function getFullNameAttribute(): string
    { return "{$this->first_name} {$this->last_name}"; }

    public function getScoreAttribute(): int
    { return $this->creditScore?->score ?? 300; }
}
