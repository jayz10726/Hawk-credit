<?php
namespace App\Http\Controllers\OrgAdmin;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $orgId = auth()->user()->organization_id;
        $loans = Loan::with('user')
            ->where('organization_id',$orgId)
            ->when($request->status, fn($q,$s) => $q->where('status',$s))
            ->orderByDesc('created_at')->paginate(20);
        return view('admin.loans.index', compact('loans'));
    }

    public function show(Loan $loan)
    {
        $loan->load(['user','installments','repayments']);
        return view('admin.loans.show', compact('loan'));
    }
}
