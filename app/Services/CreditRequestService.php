<?php
namespace App\Services;
use App\Models\{User, CreditRequest, Organization};
use App\Events\CreditRequestSubmitted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreditRequestService
{
    public function __construct(private AuditService $audit) {}

    public function submit(User $user, array $data): CreditRequest
    {
        $this->assertEligible($user, $data['amount_requested']);

        return DB::transaction(function () use ($user, $data) {
            $req = CreditRequest::create([
                'uuid'                   => Str::uuid(),
                'reference_code'         => $this->generateRef($user->organization_id),
                'user_id'                => $user->id,
                'organization_id'        => $user->organization_id,
                'amount_requested'       => $data['amount_requested'],
                'purpose'                => $data['purpose'],
                'purpose_details'        => $data['purpose_details'] ?? null,
                'tenure_months'          => $data['tenure_months'],
                'status'                 => CreditRequest::STATUS_SUBMITTED,
                'score_at_application'   => $user->creditScore?->score ?? 300,
                'documents'              => $data['documents'] ?? null,
            ]);

            event(new CreditRequestSubmitted($req));
            $this->audit->log($user, 'credit_request.submitted', $req);

            return $req;
        });
    }

    private function assertEligible(User $user, float $amount): void
    {
        $cs = $user->creditScore;
        abort_if(!$cs, 422, 'Credit profile not found.');
 abort_if($cs->score < 400, 422, 'Credit score too low (minimum 400 required).');
        abort_if($amount > $cs->available_credit, 422, 'Amount exceeds your available credit limit.');
        abort_if($cs->active_loans_count >= 3, 422, 'Maximum 3 active loans allowed.');
        // Check for duplicate pending request
        abort_if(
            $user->creditRequests()->pending()->where('amount_requested', $amount)->exists(),
            422, 'A similar pending request already exists.'
        );
    }

    private function generateRef(int $orgId): string
    {
        $count = CreditRequest::where('organization_id', $orgId)->count() + 1;
        return 'CR-' . date('Y') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
