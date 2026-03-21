<?php
namespace App\Http\Controllers\UserCtrl;
use App\Http\Controllers\Controller;
use App\Models\{Loan, LoanInstallment};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user  = auth()->user();
        $score = $user->creditScore;

        $activeLoans = Loan::where('user_id',$user->id)
            ->where('status','active')
            ->withCount(['installments as overdue_count' => fn($q) => $q->where('status','overdue')])
            ->get();

        // Next upcoming payment
        $nextDue = LoanInstallment::where('user_id',$user->id)
            ->where('status','pending')
            ->orderBy('due_date')->first();

        return view('user.dashboard', compact('score','activeLoans','nextDue'));
    }
}
