<?php

namespace App\Services;

use App\Models\User;
use App\Models\CreditScore;
use App\Models\CreditScoreHistory;

class CreditScoreService
{
    public function recalculate(User $user): CreditScore
    {
        $score = $user->creditScore;

        if (!$score) {
            $score = CreditScore::create([
                'user_id'         => $user->id,
                'organization_id' => $user->organization_id,
                'score'           => 300,
                'band'            => 'very_poor',
                'credit_limit'    => 0,
                'available_credit'=> 0,
            ]);
        }

        $oldScore = $score->score;

        // ── Factor 1: Repayment History (40%) ──────────────────────
        $total = max(1, $score->on_time_payments + $score->late_payments + $score->missed_payments);
        $R = $score->on_time_payments / $total;

        // ── Factor 2: Credit Utilization (25%) ─────────────────────
        $U = $score->credit_limit > 0
            ? max(0, 1 - (($score->credit_limit - $score->available_credit) / $score->credit_limit))
            : 0.5;

        // ── Factor 3: Length of Credit History (15%) ───────────────
        $monthsOld = $user->created_at->diffInMonths(now());
        $L = min(1, $monthsOld / 60);

        // ── Factor 4: Late Payment Penalty (10%) ───────────────────
        $P = max(0, 1 - ($score->late_payments * 0.1) - ($score->missed_payments * 0.3));

        // ── Factor 5: Debt-to-Income Ratio (10%) ───────────────────
        $monthlyIncome = max(1, $user->monthly_income ?? 1);
        $monthlyDebt   = $score->credit_limit > 0
            ? ($score->credit_limit - $score->available_credit) * 0.05
            : 0;
        $D = max(0, 1 - min(1, $monthlyDebt / $monthlyIncome));

        // ── Final Score ─────────────────────────────────────────────
        $newScore = (int) round(300 + 550 * (
            0.40 * $R +
            0.25 * $U +
            0.15 * $L +
            0.10 * $P +
            0.10 * $D
        ));

        $newScore = max(300, min(850, $newScore));
        $band     = $this->getBand($newScore);

        $score->update([
            'score'            => $newScore,
            'band'             => $band,
            'risk_category'    => $newScore >= 660 ? 'low' : ($newScore >= 540 ? 'medium' : 'high'),
            'last_calculated_at' => now(),
        ]);

        // ── Save history record ─────────────────────────────────────
        if ($oldScore !== $newScore) {
            CreditScoreHistory::create([
                'user_id'         => $user->id,
                'organization_id' => $user->organization_id,
                'old_score'       => $oldScore,
                'new_score'       => $newScore,
                'delta'           => $newScore - $oldScore,
                'trigger_event'   => 'recalculation',
                'created_at'      => now(),
            ]);
        }

        return $score->fresh();
    }

    private function getBand(int $score): string
    {
        return match(true) {
            $score >= 780 => 'exceptional',
            $score >= 720 => 'excellent',
            $score >= 660 => 'good',
            $score >= 600 => 'fair',
            $score >= 540 => 'poor',
            default       => 'very_poor',
        };
    }
}