<?php

namespace App\Http\Controllers\UserCtrl;

use App\Http\Controllers\Controller;
use App\Models\CreditRequest;
use App\Models\CreditScore;
use App\Models\Loan;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreditRequestController extends Controller
{
    public function __construct(private AuditService $auditService) {}

    public function index()
    {
        $requests = CreditRequest::where('user_id', auth()->id())
            ->orderByDesc('created_at')->paginate(15);
        return view('user.requests.index', compact('requests'));
    }

    public function create()
    {
        $user        = auth()->user();
        $score       = $user->creditScore;
        $eligibility = $this->checkEligibility($user, $score);
        return view('user.requests.create', compact('score', 'eligibility'));
    }

    public function store(Request $request)
    {
        $user        = auth()->user();
        $score       = $user->creditScore;
        $eligibility = $this->checkEligibility($user, $score);

        if (!$eligibility['eligible']) {
            return back()->withErrors(['eligibility' => $eligibility['reason']]);
        }

        $data = $request->validate([
            'amount_requested' => 'required|numeric|min:1000|max:' . ($score->available_credit ?? 0),
            'tenure_months'    => 'required|integer|in:3,6,12,18,24,36',
            'purpose'          => 'required|string|max:255',
            'purpose_details'  => 'nullable|string|max:2000',
            'documents.*'      => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png',
        ]);

        $docPaths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $docPaths[] = $file->store('credit-docs/' . date('Y/m'), 'public');
            }
        }

        $cr = CreditRequest::create([
            'uuid'                 => (string) Str::uuid(),
            'reference_code'       => 'CR-' . date('Y') . '-' . str_pad(CreditRequest::count() + 1, 5, '0', STR_PAD_LEFT),
            'user_id'              => $user->id,
            'organization_id'      => $user->organization_id,
            'amount_requested'     => $data['amount_requested'],
            'tenure_months'        => $data['tenure_months'],
            'purpose'              => $data['purpose'],
            'purpose_details'      => $data['purpose_details'] ?? null,
            'score_at_application' => $score->score,
            'status'               => 'submitted',
            'documents'            => $docPaths,
        ]);

        $this->auditService->log($user, 'credit_request.submitted', $cr);

        return redirect()->route('user.requests.show', $cr)
            ->with('success', 'Application submitted successfully!');
    }

    public function show(CreditRequest $request)
    {
        abort_unless($request->user_id === auth()->id(), 403);
        return view('user.requests.show', ['req' => $request]);
    }

    private function checkEligibility(\App\Models\User $user, ?CreditScore $score): array
    {
        if (!$score) {
            return [
                'eligible' => false,
                'reason'   => 'You do not have a credit profile yet. Please contact your organization admin.',
                'checks'   => [
                    ['label' => 'Credit score ≥ 540',            'passed' => false],
                    ['label' => 'Available credit limit > 0',     'passed' => false],
                    ['label' => 'No pending applications',        'passed' => false],
                    ['label' => 'No defaulted loans',             'passed' => false],
                    ['label' => 'Organization account is active', 'passed' => false],
                    ['label' => 'Organization has credit funds',  'passed' => false],
                ],
            ];
        }

        $scoreOk   = $score->score >= 540;
        $creditOk  = $score->available_credit > 0;
        $noPending = !CreditRequest::where('user_id', $user->id)
            ->whereIn('status', ['submitted', 'under_review'])->exists();
        $noDefault = !Loan::where('user_id', $user->id)
            ->where('status', 'defaulted')->exists();
        $orgActive = $user->organization?->status === 'active';
        $poolOk    = ($user->organization?->available_credit_pool ?? 0) > 0;

        $eligible = $scoreOk && $creditOk && $noPending && $noDefault && $orgActive && $poolOk;

        $reason = match(true) {
            !$scoreOk   => "Your credit score of {$score->score} is below the minimum required score of 540.",
            !$creditOk  => 'You have no available credit limit. Please contact your admin.',
            !$noPending => 'You already have a pending application under review.',
            !$noDefault => 'You have a defaulted loan. Please settle it before applying.',
            !$orgActive => 'Your organization account is currently suspended.',
            !$poolOk    => 'Your organization has insufficient credit pool funds.',
            default     => 'You are eligible to apply.',
        };

        return [
            'eligible' => $eligible,
            'reason'   => $reason,
            'checks'   => [
                ['label' => 'Credit score ≥ 540',            'passed' => $scoreOk],
                ['label' => 'Available credit limit > 0',     'passed' => $creditOk],
                ['label' => 'No pending applications',        'passed' => $noPending],
                ['label' => 'No defaulted loans',             'passed' => $noDefault],
                ['label' => 'Organization account is active', 'passed' => $orgActive],
                ['label' => 'Organization has credit funds',  'passed' => $poolOk],
            ],
        ];
    }
}