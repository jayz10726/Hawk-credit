<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasOne, BelongsTo};

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid','name','slug','email','phone','address','logo_path',
        'status','subscription_tier','credit_pool','available_credit_pool','settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'credit_pool' => 'decimal:2',
        'available_credit_pool' => 'decimal:2',
    ];

    // ── Relationships ───────────────────────────────────────────
    public function users(): HasMany
    { return $this->hasMany(User::class); }

    public function creditRequests(): HasMany
    { return $this->hasMany(CreditRequest::class); }

    public function loans(): HasMany
    { return $this->hasMany(Loan::class); }

    // ── Scopes ─────────────────────────────────────────────────
    public function scopeActive($q) { return $q->where('status', 'active'); }

    // ── Helpers ────────────────────────────────────────────────
    public function hasSufficientPool(float $amount): bool
    { return $this->available_credit_pool >= $amount; }

    public function deductFromPool(float $amount): void
    {
        $this->decrement('available_credit_pool', $amount);
    }
}

