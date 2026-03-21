<?php
namespace App\Services;
use App\Models\{User, CreditRequest};
use App\Events\{CreditRequestApproved, CreditRequestRejected};
use Illuminate\Support\Facades\DB;

class CreditApprovalService
{
    public function __construct(
        private LoanService  $loanService,
        private AuditService $audit
    ) {}

    public function approve(CreditRequest $req, User $admin, array $data): CreditRequest
    {
        abort_unless($req->isApprovable(), 422, 'Request is not in an approvable state.');

        return DB::transaction(function () use ($req, $admin, $data) {
            $req->update([
                'status'          => CreditRequest::STATUS_APPROVED,
                'amount_approved' => $data['amount_approved'],
                'interest_rate'   => $data['interest_rate'],
                'reviewed_by'     => $admin->id,
                'review_notes'    => $data['notes'] ?? null,
            ]);

            // Create the loan immediately on approval
            $loan = $this->loanService->createFromRequest($req);

            $req->update(['status' => CreditRequest::STATUS_DISBURSED, 'disbursed_at' => now()]);
event(new CreditRequestApproved($req, $loan));
            $this->audit->log($admin, 'credit_request.approved', $req, ['loan_id' => $loan->id]);

            return $req->fresh();
        });
    }

    public function reject(CreditRequest $req, User $admin, string $reason): CreditRequest
    {
        abort_unless($req->isApprovable(), 422, 'Request is not in a rejectable state.');

        $req->update([
            'status'           => CreditRequest::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'reviewed_by'      => $admin->id,
        ]);

        event(new CreditRequestRejected($req));
        $this->audit->log($admin, 'credit_request.rejected', $req);

        return $req->fresh();
    }
}
