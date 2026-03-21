<?php
namespace App\Http\Controllers\OrgAdmin;
use App\Http\Controllers\Controller;
use App\Models\{Loan, Repayment, CreditRequest, CreditScore, LoanInstallment};
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __invoke(Request $request)
    {
        $orgId = auth()->user()->organization_id;
        $data = [
            'monthly_repayments'   => Repayment::where('organization_id',$orgId)
                ->where('status','confirmed')
                ->selectRaw("strftime('%Y-%m', paid_at) as month, SUM(amount) as total")
                ->groupBy('month')->orderBy('month')->limit(12)->get(),
            'score_distribution'   => CreditScore::where('organization_id',$orgId)
                ->selectRaw('band, COUNT(*) as count')->groupBy('band')->get(),
            'overdue_installments' => LoanInstallment::where('organization_id',$orgId)
                ->where('status','overdue')->count(),
            'collection_rate'      => $this->collectionRate($orgId),
        ];
        return view('admin.analytics', compact('data'));
    }
    private function collectionRate(int $orgId): float
    {
        $expected = LoanInstallment::where('organization_id',$orgId)
            ->whereIn('status',['paid','overdue','partial'])->sum('amount_due');
        $collected = Repayment::where('organization_id',$orgId)
            ->where('status','confirmed')->sum('amount');
        return $expected > 0 ? round(($collected / $expected) * 100, 2) : 0;
    }
}
