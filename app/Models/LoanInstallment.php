<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanInstallment extends Model
{
    protected $fillable = ['loan_id','user_id','organization_id','installment_number',
        'due_date','principal_component','interest_component','amount_due',
        'amount_paid','penalty_amount','status','days_overdue','paid_at'];
    protected $casts = ['due_date'=>'date','paid_at'=>'datetime'];
    public function loan(): BelongsTo
    { return $this->belongsTo(Loan::class); }
}
