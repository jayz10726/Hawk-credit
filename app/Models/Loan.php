<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo,HasMany,HasOne};

class Loan extends Model
{
    protected $fillable = [
        'uuid','reference_code','credit_request_id','user_id','organization_id',
        'principal_amount','interest_rate','interest_amount','total_payable',
        'total_paid','outstanding_balance','monthly_installment','tenure_months',
        'start_date','end_date','next_due_date','last_payment_date',
        'status','penalty_balance',
    ];

    protected $casts = [
        'start_date'=>'date','end_date'=>'date',
        'next_due_date'=>'date','last_payment_date'=>'date',
        'principal_amount'=>'decimal:2','outstanding_balance'=>'decimal:2',
    ];

    public function creditRequest(): BelongsTo
    { return $this->belongsTo(CreditRequest::class); }
    public function user(): BelongsTo
    { return $this->belongsTo(User::class); }
    public function installments(): HasMany
    { return $this->hasMany(LoanInstallment::class)->orderBy('installment_number'); }
    public function repayments(): HasMany
    { return $this->hasMany(Repayment::class); }
    public function penalties(): HasMany
    { return $this->hasMany(Penalty::class); }

    public function scopeActive($q)
    { return $q->where('status', 'active'); }

    public function getCompletionPercentageAttribute(): float
    {
        if ($this->total_payable == 0) return 0;
        return round(($this->total_paid / $this->total_payable) * 100, 1);
    }
}

// ─── LoanInstallment ─────────────────────────────────────────────
class LoanInstallment extends Model
{
    protected $fillable = [
        'loan_id','user_id','organization_id','installment_number','due_date',
        'principal_component','interest_component','amount_due','amount_paid',
        'penalty_amount','status','days_overdue','paid_at',
    ];
    protected $casts = ['due_date'=>'date','paid_at'=>'datetime'];
    public function loan(): BelongsTo
    { return $this->belongsTo(Loan::class); }
    public function repayments(): HasMany
    { return $this->hasMany(Repayment::class, 'installment_id'); }
    public function penalty(): HasOne
    { return $this->hasOne(Penalty::class, 'installment_id'); }
    public function scopeOverdue($q)
    { return $q->where('status','overdue')->where('due_date','<',now()); }
}
//repayment
class Repayment extends Model
{
    protected $fillable = [
        'uuid','reference_code','loan_id','installment_id','user_id','organization_id',
        'amount','principal_applied','interest_applied','penalty_applied',
        'payment_method','payment_reference','status','confirmed_by','confirmed_at','paid_at',
    ];
    protected $casts = ['paid_at'=>'datetime','confirmed_at'=>'datetime','amount'=>'decimal:2'];
    public function loan(): BelongsTo
    { return $this->belongsTo(Loan::class); }
    public function user(): BelongsTo
    { return $this->belongsTo(User::class); }
}
