<?php
namespace App\Models;
use Illuminate\Database\Eloquent\{Model,SoftDeletes};
use Illuminate\Database\Eloquent\Relations\{BelongsTo,HasOne};

class CreditRequest extends Model
{
    use SoftDeletes;

    // ── Status constants ────────────────────────────────────────
    const STATUS_DRAFT       = 'draft';
    const STATUS_SUBMITTED   = 'submitted';
    const STATUS_REVIEWING   = 'under_review';
    const STATUS_APPROVED    = 'approved';
    const STATUS_REJECTED    = 'rejected';
    const STATUS_DISBURSED   = 'disbursed';

    protected $fillable = [
        'uuid','reference_code','user_id','organization_id','reviewed_by',
        'amount_requested','amount_approved','purpose','purpose_details',
        'tenure_months','interest_rate','status','current_stage',
        'score_at_application','fraud_score','rejection_reason','review_notes','documents','disbursed_at',
    ];

    protected $casts = ['documents'=>'array','disbursed_at'=>'datetime'];

    public function user(): BelongsTo
    { return $this->belongsTo(User::class); }

    public function organization(): BelongsTo
    { return $this->belongsTo(Organization::class); }

    public function reviewer(): BelongsTo
    { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function loan(): HasOne
    { return $this->hasOne(Loan::class); }

    public function isApprovable(): bool
    { return in_array($this->status, [self::STATUS_SUBMITTED, self::STATUS_REVIEWING]); }

    public function scopePending($q)
    { return $q->whereIn('status', [self::STATUS_SUBMITTED, self::STATUS_REVIEWING]); }
}

