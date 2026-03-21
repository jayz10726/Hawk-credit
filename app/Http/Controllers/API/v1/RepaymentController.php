<?php
namespace App\Http\Controllers\API\v1;
use App\Http\Controllers\Controller;
use App\Http\Resources\RepaymentResource;
use App\Models\Loan;
use App\Services\RepaymentService;
use Illuminate\Http\{Request, JsonResponse};

class RepaymentController extends Controller
{
    public function __construct(private RepaymentService $service) {}

    public function store(Request $request, Loan $loan): JsonResponse
    {
        $this->authorize('create', [\App\Models\Repayment::class, $loan]);
        $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,card,cheque',
            'payment_reference' => 'nullable|string|max:100',
        ]);
        $repayment = $this->service->process($loan, $request->user(), $request->validated());
        return response()->json(['data' => new RepaymentResource($repayment)], 201);
    }

    public function index(Loan $loan): JsonResponse
    {
        $this->authorize('view', $loan);
        return RepaymentResource::collection($loan->repayments()->latest()->paginate(20))->response();
    }
}
