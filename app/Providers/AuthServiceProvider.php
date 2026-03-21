<?php
namespace App\Providers;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\{CreditRequest, Loan, Repayment};
use App\Policies\{CreditRequestPolicy, LoanPolicy, RepaymentPolicy};

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        CreditRequest::class => CreditRequestPolicy::class,
        Loan::class          => LoanPolicy::class,
        Repayment::class     => RepaymentPolicy::class,
    ];
}
