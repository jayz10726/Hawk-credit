<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\{Organization, Loan, Repayment, CreditRequest, User};
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = [
            'total_disbursed'      => Loan::sum('principal_amount'),
            'total_collected'      => Repayment::where('status','confirmed')->sum('amount'),
            'default_rate'         => $this->defaultRate(),
            'monthly_disbursements'=> Loan::selectRaw("strftime('%Y-%m', created_at) as month, SUM(principal_amount) as total")
                ->groupBy('month')->orderBy('month')->get(),
            'monthly_repayments'   => Repayment::selectRaw("strftime('%Y-%m', paid_at) as month, SUM(amount) as total")
                ->where('status','confirmed')->groupBy('month')->orderBy('month')->get(),
            'org_performance'      => Organization::withCount('users')
                ->withSum('loans as total_disbursed','principal_amount')->get(),
        ];
        return view('super.analytics', compact('data'));
    }

    private function defaultRate(): float
    {
        $total    = Loan::count();
        $defaulted = Loan::where('status','defaulted')->count();
        return $total > 0 ? round(($defaulted / $total) * 100, 2) : 0;
    }
}
