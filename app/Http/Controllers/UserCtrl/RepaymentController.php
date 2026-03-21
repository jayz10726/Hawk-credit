<?php
namespace App\Http\Controllers\UserCtrl;
use App\Http\Controllers\Controller;
use App\Models\{Loan, Repayment, LoanInstallment};
use App\Services\{CreditScoreService, AuditService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RepaymentController extends Controller
{
    public function __construct(
        private CreditScoreService $scoreService,
        private AuditService       $auditService,
    ) {}
 public function store(Request $request, Loan $loan)
    {
        abort_unless($loan->user_id === auth()->id(), 403);
        abort_if($loan->status !== 'active', 422, 'Loan is not active.');

        $data = $request->validate([
            'amount'           => 'required|numeric|min:1|max:'.$loan->outstanding_balance,
            'payment_method'   => 'required|in:cash,bank_transfer,mobile_money,card,cheque',
            'payment_reference'=> 'nullable|string|max:100',
        ]);

        DB::transaction(function() use ($data, $loan) {
            $user = auth()->user();
            $repayment = Repayment::create([
                'uuid'              => Str::uuid(),
                'reference_code'    => 'PAY-'.date('Y').'-'.str_pad(Repayment::count()+1,6,'0',STR_PAD_LEFT),
                'loan_id'           => $loan->id,
                'user_id'           => $user->id,
                'organization_id'   => $loan->organization_id,
                'amount'            => $data['amount'],
                'payment_method'    => $data['payment_method'],
                'payment_reference' => $data['payment_reference'] ?? null,
                'status'            => 'confirmed',
                'confirmed_at'      => now(),
                'confirmed_by'      => $user->id,
                'paid_at'           => now(),
            ]);
            // Apply payment to loan balance
            $loan->decrement('outstanding_balance', $data['amount']);
            $loan->increment('total_paid', $data['amount']);
            if ($loan->fresh()->outstanding_balance <= 0) {
                $loan->update(['status' => 'completed']);
            }
            // Mark installment as paid
            $installment = LoanInstallment::where('loan_id',$loan->id)
                ->whereIn('status',['pending','overdue'])->orderBy('due_date')->first();
            if ($installment) {
                $installment->update(['status'=>'paid','amount_paid'=>$data['amount'],'paid_at'=>now()]);
            }
            // Update credit score
            $cs = $user->creditScore;
            $cs->increment('on_time_payments');
            $cs->increment('total_repaid', $data['amount']);
            $this->scoreService->recalculate($user);
            $this->auditService->log($user, 'repayment.made', $repayment);
        });
        return redirect()->route('user.loans.show',$loan)
            ->with('success','Payment of KES '.number_format($data['amount']).' recorded.');
    }
}
