<?php
namespace App\Providers;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\{CreditRequestSubmitted, CreditRequestApproved, CreditRequestRejected, RepaymentConfirmed};
use App\Listeners\{NotifyAdminsOfNewRequest, NotifyUserOfApproval, NotifyUserOfRejection, SendRepaymentReceipt};
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CreditRequestSubmitted::class => [
            NotifyAdminsOfNewRequest::class,
        ],
        CreditRequestApproved::class => [
            NotifyUserOfApproval::class,
        ],
        CreditRequestRejected::class => [
            NotifyUserOfRejection::class,
        ],
        RepaymentConfirmed::class => [
            SendRepaymentReceipt::class,
        ],
    ];
}
