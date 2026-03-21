<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repayment extends Model
{
    protected $fillable = ['uuid','reference_code','loan_id','installment_id',
        'user_id','organization_id','amount','principal_applied','interest_applied',
        'penalty_applied','payment_method','payment_reference','status',
        'confirmed_by','confirmed_at','paid_at'];
    protected $casts = ['paid_at'=>'datetime','confirmed_at'=>'datetime'];
    public function loan(): BelongsTo
    { return $this->belongsTo(Loan::class); }
}
