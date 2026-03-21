<?php
namespace App\Services;
use App\Models\{Loan, CreditRequest, Repayment, User, CreditScore};
use Illuminate\Support\Facades\{Cache, DB};

class AnalyticsService
{
    // ── Org Admin Dashboard ───────────────────────────────────
    public function orgDashboard(int $orgId): array
    {
        return Cache::remember("analytics:org:{$orgId}", 300, function () use ($orgId) {
            return [
                'total_active_loans'    => Loan::where('organization_id', $orgId)->active()->count(),
                'total_disbursed'       => Loan::where('organization_id', $orgId)->sum('principal_amount'),
                'total_outstanding'     => Loan::where('organization_id', $orgId)->sum('outstanding_balance'),
                'total_repaid'          => Repayment::where('organization_id', $orgId)->where('status','confirmed')->sum('amount'),
                'pending_requests'      => CreditRequest::where('organization_id', $orgId)->pending()->count(),
                'avg_credit_score'      => CreditScore::where('organization_id', $orgId)->avg('score'),
                'default_rate'          => $this->calcDefaultRate($orgId),
                'monthly_repayments'    => $this->monthlyRepayments($orgId),
                'score_distribution'    => $this->scoreDistribution($orgId),
            ];
        });
    }

    // ── Super Admin Global Dashboard ──────────────────────────
    public function globalDashboard(): array
    {
        return Cache::remember('analytics:global', 300, function () {
            return [
                'total_orgs'       => \App\Models\Organization::active()->count(),
                'total_users'      => User::active()->count(),
                'total_disbursed'  => Loan::sum('principal_amount'),
                'total_collected'  => Repayment::where('status','confirmed')->sum('amount'),
                'global_default_rate' => $this->calcDefaultRate(),
                'new_requests_trend'  => $this->requestsTrend(),
            ];
        });
    }
private function calcDefaultRate(?int $orgId = null): float
    {
        $q = Loan::query();
        if ($orgId) $q->where('organization_id', $orgId);
        $total    = $q->count();
        $defaults = (clone $q)->where('status', 'defaulted')->count();
        return $total > 0 ? round(($defaults / $total) * 100, 2) : 0;
    }

    private function monthlyRepayments(int $orgId): array
    {
        return Repayment::where('organization_id', $orgId)
            ->where('status','confirmed')
            ->where('paid_at', '>=', now()->subMonths(12))
            ->selectRaw('YEAR(paid_at) as yr, MONTH(paid_at) as mo, SUM(amount) as total')
            ->groupBy('yr','mo')->orderBy('yr')->orderBy('mo')
            ->get()->toArray();
    }

    private function scoreDistribution(int $orgId): array
    {
        return CreditScore::where('organization_id', $orgId)
            ->selectRaw('band, COUNT(*) as count')
            ->groupBy('band')->get()->toArray();
    }

    private function requestsTrend(): array
    {
        return CreditRequest::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->orderBy('date')->get()->toArray();
    }
}
