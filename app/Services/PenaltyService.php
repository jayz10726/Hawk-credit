<?php
namespace App\Services;
use App\Models\{LoanInstallment, Penalty, Loan};
use Illuminate\Support\Facades\DB;

class PenaltyService
{
    private const DAILY_RATE = 0.005; // 0.5% per day overdue

    public function applyDailyPenalties(): int
    {
        $count = 0;
        $today = today();

        // Find all overdue installments not yet fully paid
        LoanInstallment::where('due_date', '<', $today)
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->with('loan')
            ->chunkById(200, function ($installments) use (&$count, $today) {
                foreach ($installments as $inst) {
                    $days = $inst->due_date->diffInDays($today);
                    if ($days <= 0) continue;

                    DB::transaction(function () use ($inst, $days) {
                        $overdue   = $inst->amount_due - $inst->amount_paid;
                        $penAmt    = round($overdue * self::DAILY_RATE * $days, 2);

                        Penalty::updateOrCreate(
                            ['installment_id' => $inst->id],
                            [
                                'loan_id'         => $inst->loan_id,
                                'organization_id' => $inst->organization_id,
                                'days_overdue'    => $days,
                                'penalty_rate'    => self::DAILY_RATE,
                                'penalty_amount'  => $penAmt,
                            ]
                        );

                        $inst->update(['status' => 'overdue', 'days_overdue' => $days, 'penalty_amount' => $penAmt]);
                        Loan::where('id', $inst->loan_id)->update(['penalty_balance' => $penAmt]);
                    });
                    $count++;
                }
            });
 return $count;
    }
}
