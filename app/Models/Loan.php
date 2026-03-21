<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    protected $fillable = [
        'uuid', 'reference_code', 'credit_request_id', 'user_id',
        'organization_id', 'principal_amount', 'interest_rate', 'interest_amount',
        'total_payable', 'total_paid', 'outstanding_balance', 'monthly_installment',
        'tenure_months', 'start_date', 'end_date', 'next_due_date', 'last_payment_date',
        'status', 'penalty_balance',
    ];

    protected $casts = [
        'start_date'      => 'date',
        'end_date'        => 'date',
        'next_due_date'   => 'date',
        'last_payment_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(LoanInstallment::class)->orderBy('installment_number');
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(Repayment::class)->orderByDesc('paid_at');
    }

    public function getCompletionPercentageAttribute(): float
    {
        return $this->total_payable > 0
            ? round(($this->total_paid / $this->total_payable) * 100, 1)
            : 0;
    }
}