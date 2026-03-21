<?php
namespace App\Http\Controllers\UserCtrl;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::where('user_id', auth()->id())
            ->orderByDesc('created_at')->paginate(10);
        return view('user.loans.index', compact('loans'));
    }

    public function show(Loan $loan)
    {
        abort_unless($loan->user_id === auth()->id(), 403);
        $loan->load(['installments','repayments']);
        return view('user.loans.show', compact('loan'));
    }
}
