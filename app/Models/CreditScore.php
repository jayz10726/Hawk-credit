<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class CreditScore extends Model
{
    protected $fillable = [
        'user_id','organization_id','score','band','risk_category',
        'credit_limit','available_credit','on_time_payments','late_payments',
        'missed_payments','active_loans_count','total_borrowed','total_repaid',
        'debt_to_income_ratio','last_calculated_at',
    ];

    protected $casts = [
        'credit_limit'       => 'decimal:2',
        'available_credit'   => 'decimal:2',
        'last_calculated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    { return $this->belongsTo(User::class); }

    public function history(): HasMany
    { return $this->hasMany(CreditScoreHistory::class, 'user_id', 'user_id'); }

    public function isEligibleForCredit(float $amount): bool
    {
        return $this->score >= 400 && $this->available_credit >= $amount;
    }

    public function getRiskLevelColor(): string
    {
        return match($this->risk_category) {
            'low'       => 'green',
            'medium'    => 'yellow',
            'high'      => 'orange',
            'very_high' => 'red',
            default     => 'gray',
        };
    }
}
