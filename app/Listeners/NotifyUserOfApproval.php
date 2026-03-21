<?php
namespace App\Listeners;
use App\Events\CreditRequestApproved;
use App\Notifications\CreditRequestApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyUserOfApproval implements ShouldQueue
{
    public string $queue = 'notifications';

    public function handle(CreditRequestApproved $event): void
    {
        $event->creditRequest->user->notify(
            new CreditRequestApprovedNotification($event->creditRequest, $event->loan)
        );
    }
}
