<?php
namespace App\Http\Controllers\OrgAdmin;
use App\Http\Controllers\Controller;
use App\Models\{CreditRequest, CreditScore};
use App\Services\{LoanService, CreditScoreService, AuditService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditRequestController extends Controller
{
    public function __construct(
        private LoanService        $loanService,
        private CreditScoreService $scoreService,
        private AuditService       $auditService,
    ) {}

    public function index(Request $request)
    {
        $orgId = auth()->user()->organization_id;
        $requests = CreditRequest::with('user')
            ->where('organization_id',$orgId)
            ->when($request->status, fn($q,$s) => $q->where('status',$s))
            ->orderByDesc('created_at')->paginate(20);
        return view('admin.requests.index', compact('requests'));
    }

    public function show(CreditRequest $request)
    {
        $request->load(['user','user.creditScore']);
        return view('admin.requests.show', ['req' => $request]);
    }

    public function approve(Request $httpRequest, CreditRequest $req)
    {
        $httpRequest->validate([
            'amount_approved' => 'required|numeric|min:1|max:'.$req->amount_requested,
            'interest_rate'   => 'required|numeric|min:0|max:100',
            'notes'           => 'nullable|string|max:1000',
        ]);

        DB::transaction(function() use ($httpRequest, $req) {
            $req->update([
                'status'          => 'approved',
                'amount_approved' => $httpRequest->amount_approved,
                'interest_rate'   => $httpRequest->interest_rate,
                'review_notes'    => $httpRequest->notes,
                'reviewed_by'     => auth()->id(),
                'disbursed_at'    => now(),
            ]);
            // Create the loan + payment schedule
            $loan = $this->loanService->createFromRequest($req);
            $req->update(['status' => 'disbursed']);
            // Recalculate credit score
            $this->scoreService->recalculate($req->user);
            $this->auditService->log(auth()->user(),
                'credit_request.approved', $req, ['loan_id' => $loan->id]);
        });

        return redirect()->route('admin.requests.index')
            ->with('success','Request approved and loan created.');
    }

    public function reject(Request $httpRequest, CreditRequest $req)
  {
        $httpRequest->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);
        $req->update([
            'status'           => 'rejected',
            'rejection_reason' => $httpRequest->reason,
            'reviewed_by'      => auth()->id(),
        ]);
        $this->auditService->log(auth()->user(),
            'credit_request.rejected', $req);
        return redirect()->route('admin.requests.index')
            ->with('success','Request rejected.');
    }
}
