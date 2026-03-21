<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $loans = Loan::with(['user','organization'])
            ->when($request->status, fn($q,$s)=>$q->where('status',$s))
            ->when($request->org, fn($q,$o)=>$q->where('organization_id',$o))
            ->when($request->search, fn($q,$s)=>$q->whereHas('user',fn($u)=>$u->where('email','like',"%$s%")))
            ->orderByDesc('created_at')->paginate(20);
        $orgs = \App\Models\Organization::pluck('name','id');
        $stats = [
            'total'     => Loan::count(),
            'active'    => Loan::where('status','active')->count(),
            'completed' => Loan::where('status','completed')->count(),
            'defaulted' => Loan::where('status','defaulted')->count(),
            'disbursed' => Loan::sum('principal_amount'),
            'outstanding'=> Loan::where('status','active')->sum('outstanding_balance'),
        ];
        return view('super.loans.index', compact('loans','orgs','stats'));
    }
    public function show(Loan $loan)
    {
        $loan->load(['user','organization','installments','repayments']);
        return view('super.loans.show', compact('loan'));
    }
}
