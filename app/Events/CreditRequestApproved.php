<?php
namespace App\Events;
use App\Models\{CreditRequest, Loan};
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreditRequestApproved
{
    use Dispatchable, SerializesModels;
    public function __construct(
        public readonly CreditRequest $creditRequest,
        public readonly Loan          $loan,
    ) {}
}

