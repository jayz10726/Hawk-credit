<?php
namespace App\Console\Commands;
use App\Services\PenaltyService;
use Illuminate\Console\Command;

class ApplyDailyPenalties extends Command
{
    protected $signature   = 'hawks:apply-penalties';
    protected $description = 'Apply daily penalties to overdue installments';

    public function handle(PenaltyService $svc): void
    {
        $n = $svc->applyDailyPenalties();
        $this->info("Applied penalties to {$n} installments.");
    }
}

