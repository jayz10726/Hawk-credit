<?php
namespace App\Events;
use App\Models\Repayment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RepaymentConfirmed
{
    use Dispatchable, SerializesModels;
    public function __construct(public readonly Repayment $repayment) {}
}