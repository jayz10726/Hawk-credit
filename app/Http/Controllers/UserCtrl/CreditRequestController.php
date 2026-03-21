<?php
namespace App\Http\Controllers\UserCtrl;
use App\Http\Controllers\Controller;
use App\Models\CreditRequest;
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
        $score = auth()->user()->creditScore;
        return view('user.requests.create', compact('score'));
    }

    public function store(Request $request)
    {
        $user  = auth()->user();
        $score = $user->creditScore;

        // Basic eligibility check
        if (!$score || $score->score < 300) {
            return back()->withErrors(['amount_requested' => 'Your credit score is too low to apply.']);
        }

        $data = $request->validate([
            'amount_requested' => 'required|numeric|min:1000|max:'.$score->available_credit,
            'tenure_months'    => 'required|integer|in:3,6,12,18,24,36',
            'purpose'          => 'required|string|max:255',
            'purpose_details'  => 'nullable|string|max:2000',
            'documents.*'      => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png',
        ]);

        // Handle document uploads
        $docPaths = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $docPaths[] = $file->store('credit-docs/'.date('Y/m'),'public');
            }
        }

        $cr = CreditRequest::create([
            'uuid'                 => Str::uuid(),
            'reference_code'       => 'CR-'.date('Y').'-'.str_pad(CreditRequest::count()+1,5,'0',STR_PAD_LEFT),
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
        $this->auditService->log($user,'credit_request.submitted',$cr);
        return redirect()->route('user.requests.show',$cr)
            ->with('success','Application submitted successfully!');
    }

    public function show(CreditRequest $request)
{
        abort_unless($request->user_id === auth()->id(), 403);
        return view('user.requests.show', ['req' => $request]);
    }
}
