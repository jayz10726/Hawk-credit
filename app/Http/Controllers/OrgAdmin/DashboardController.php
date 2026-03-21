<?php
namespace App\Http\Controllers\OrgAdmin;
use App\Http\Controllers\Controller;
use App\Models\{Loan, CreditRequest, User, Repayment};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $orgId = auth()->user()->organization_id;

        $stats = [
            'total_active_loans'  => Loan::where('organization_id',$orgId)->where('status','active')->count(),
            'total_disbursed'     => Loan::where('organization_id',$orgId)->sum('principal_amount'),
            'total_outstanding'   => Loan::where('organization_id',$orgId)->where('status','active')->sum('outstanding_balance'),
            'pending_requests'    => CreditRequest::where('organization_id',$orgId)->where('status','submitted')->count(),
            'total_members'       => User::where('organization_id',$orgId)->count(),
            'monthly_repayments'  => Repayment::where('organization_id',$orgId)
                ->where('status','confirmed')
                ->selectRaw("strftime('%Y-%m', paid_at) as month, SUM(amount) as total")
                ->groupBy('month')->orderBy('month')->limit(12)->get(),
            'score_distribution'  => \App\Models\CreditScore::where('organization_id',$orgId)
                ->selectRaw('band, COUNT(*) as count')->groupBy('band')->get(),
        ];

        $pendingRequests = CreditRequest::with('user')
            ->where('organization_id',$orgId)->where('status','submitted')
            ->orderByDesc('created_at')->take(10)->get();

        return view('admin.dashboard', compact('stats','pendingRequests'));
    }
}
