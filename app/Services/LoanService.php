<?php
namespace App\Services;
use App\Models\{CreditRequest, Loan, LoanInstallment, CreditScore};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LoanService
{
    public function createFromRequest(CreditRequest $req): Loan
    {
        return DB::transaction(function () use ($req) {
            $principal  = $req->amount_approved;
            $annualRate = $req->interest_rate;
            $months     = $req->tenure_months;
            $monthlyPMT = $this->calculatePMT($principal, $annualRate, $months);
            $totalPayable = round($monthlyPMT * $months, 2);
            $interestAmt  = round($totalPayable - $principal, 2);

            $loan = Loan::create([
                'uuid'               => Str::uuid(),
                'reference_code'     => 'LN-' . date('Y') . '-' . str_pad(Loan::count() + 1, 5, '0', STR_PAD_LEFT),
                'credit_request_id'  => $req->id,
                'user_id'            => $req->user_id,
                'organization_id'    => $req->organization_id,
                'principal_amount'   => $principal,
                'interest_rate'      => $annualRate,
                'interest_amount'    => $interestAmt,
                'total_payable'      => $totalPayable,
                'outstanding_balance'=> $totalPayable,
                'monthly_installment'=> $monthlyPMT,
                'tenure_months'      => $months,
                'start_date'         => today(),
                'end_date'           => today()->addMonths($months),
                'next_due_date'      => today()->addMonth(),
                'status'             => 'active',
            ]);

            // Generate amortization schedule
            $this->generateSchedule($loan);

            // Deduct from org credit pool
$req->organization->deductFromPool($principal);

            // Update user credit score record
            CreditScore::where('user_id', $req->user_id)->increment('active_loans_count');
            CreditScore::where('user_id', $req->user_id)->increment('total_borrowed', $principal);

            return $loan;
        });
    }

    // ── PMT Formula (standard mortgage amortization) ──────────
    private function calculatePMT(float $principal, float $annualRate, int $months): float
    {
        if ($annualRate == 0) return round($principal / $months, 2);
        $r = ($annualRate / 100) / 12;  // monthly rate
        $pmt = $principal * ($r * pow(1 + $r, $months)) / (pow(1 + $r, $months) - 1);
        return round($pmt, 2);
    }

    // ── Amortization Schedule Generator ──────────────────────
    private function generateSchedule(Loan $loan): void
    {
        $balance  = $loan->principal_amount;
        $r        = ($loan->interest_rate / 100) / 12;
        $dueDate  = Carbon::parse($loan->start_date)->addMonth();
        $rows     = [];

        for ($i = 1; $i <= $loan->tenure_months; $i++) {
            $interestComp  = round($balance * $r, 2);
            $principalComp = $loan->monthly_installment - $interestComp;
            // Adjust last installment for rounding
            if ($i === $loan->tenure_months) $principalComp = $balance;
            $balance -= $principalComp;

            $rows[] = [
                'loan_id'              => $loan->id,
                'user_id'              => $loan->user_id,
                'organization_id'      => $loan->organization_id,
                'installment_number'   => $i,
                'due_date'             => $dueDate->toDateString(),
                'principal_component'  => $principalComp,
                'interest_component'   => $interestComp,
                'amount_due'           => $loan->monthly_installment,
                'status'               => 'pending',
                'created_at'           => now(),
                'updated_at'           => now(),
            ];
            $dueDate->addMonth();
        }

        LoanInstallment::insert($rows); // bulk insert for performance
    }
}
