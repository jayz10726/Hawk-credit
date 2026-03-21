<?php
namespace App\Services;
use App\Models\{User, Loan, LoanInstallment, Repayment, CreditScore};
use App\Events\RepaymentConfirmed;
use App\Jobs\RecalculateCreditScore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RepaymentService
{
    public function __construct(private AuditService $audit) {}

    public function process(Loan $loan, User $user, array $data): Repayment
    {
        abort_if($loan->status !== 'active', 422, 'Loan is not active.');
        abort_if($loan->outstanding_balance <= 0, 422, 'Loan is already fully paid.');

        return DB::transaction(function () use ($loan, $user, $data) {
            $amount = (float) $data['amount'];
            $installment = $loan->installments()
                ->whereIn('status', ['pending','partial','overdue'])
                ->orderBy('installment_number')->first();

            // Allocate: penalty first, then interest, then principal
            $penaltyApplied   = min($amount, $loan->penalty_balance);
            $remaining        = $amount - $penaltyApplied;
            $interestApplied  = 0; $principalApplied = 0;

            if ($installment) {
                $interestApplied = min($remaining, $installment->interest_component);
                $remaining -= $interestApplied;
                $principalApplied = min($remaining, $installment->principal_component);
            }

            $repayment = Repayment::create([
                'uuid'              => Str::uuid(),
                'reference_code'    => 'PMT-' . now()->format('YmdHis') . '-' . rand(100,999),
                'loan_id'           => $loan->id,
                'installment_id'    => $installment?->id,
                'user_id'           => $user->id,
                'organization_id'   => $loan->organization_id,
                'amount'            => $amount,
                'principal_applied' => $principalApplied,
                'interest_applied'  => $interestApplied,
 'penalty_applied'   => $penaltyApplied,
                'payment_method'    => $data['payment_method'],
                'payment_reference' => $data['payment_reference'] ?? null,
                'status'            => 'confirmed',
                'confirmed_at'      => now(),
                'paid_at'           => now(),
            ]);

            // Update loan balances
            $loan->increment('total_paid', $amount);
            $loan->decrement('outstanding_balance', $principalApplied + $interestApplied);
            $loan->decrement('penalty_balance', $penaltyApplied);
            $loan->update(['last_payment_date' => today()]);

            // Update installment
            if ($installment) {
                $installment->increment('amount_paid', $amount);
                $newStatus = $installment->amount_paid >= $installment->amount_due
                    ? 'paid' : 'partial';
                $installment->update(['status' => $newStatus, 'paid_at' => $newStatus === 'paid' ? now() : null]);
            }

            // Update credit score stats
            $wasLate = $installment && $installment->due_date->isPast();
            $wasLate
                ? CreditScore::where('user_id', $user->id)->increment('late_payments')
                : CreditScore::where('user_id', $user->id)->increment('on_time_payments');
            CreditScore::where('user_id', $user->id)->increment('total_repaid', $amount);

            // Mark loan completed if fully paid
            if ($loan->fresh()->outstanding_balance <= 0) {
                $loan->update(['status' => 'completed']);
                CreditScore::where('user_id', $user->id)->decrement('active_loans_count');
            }

            // Async: recalculate credit score
            RecalculateCreditScore::dispatch($user)->onQueue('credit-scoring');
            event(new RepaymentConfirmed($repayment));
            $this->audit->log($user, 'repayment.made', $repayment);

            return $repayment;
        });
    }
}
