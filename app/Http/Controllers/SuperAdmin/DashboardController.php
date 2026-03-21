<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\{Organization, User, Loan, CreditRequest};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $stats = [
            'total_orgs'         => Organization::count(),
            'active_orgs'        => Organization::where('status','active')->count(),
            'total_users'        => User::count(),
            'total_disbursed'    => Loan::sum('principal_amount'),
            'total_collected'    => \App\Models\Repayment::where('status','confirmed')->sum('amount'),
            'active_loans'       => Loan::where('status','active')->count(),
            'pending_requests'   => CreditRequest::where('status','submitted')->count(),
            'defaulted_loans'    => Loan::where('status','defaulted')->count(),
            // 30-day trend for chart
            'new_requests_trend' => CreditRequest::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at','>=',now()->subDays(30))
                ->groupBy('date')->orderBy('date')->get(),
        ];

        $organizations = Organization::withCount('users')
            ->orderByDesc('created_at')->paginate(10);

        return view('super.dashboard', compact('stats','organizations'));
    }
}
