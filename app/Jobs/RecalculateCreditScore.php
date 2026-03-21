<?php
namespace App\Jobs;
use App\Models\User;
use App\Services\CreditScoreService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class RecalculateCreditScore implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int    $tries   = 3;
    public int    $timeout = 60;

    public function __construct(private User $user) {}

    // ShouldBeUnique — prevents duplicate score jobs per user
    public function uniqueId(): string
    { return 'score_' . $this->user->id; }

    public function handle(CreditScoreService $svc): void
    {
        $svc->recalculate($this->user);
    }

    public function failed(\Throwable $e): void
    {
        \Log::error('Credit score job failed', ['user' => $this->user->id, 'error' => $e->getMessage()]);
    }
}

