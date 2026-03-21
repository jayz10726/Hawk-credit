<?php
namespace App\Http\Controllers\API\v1;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCreditRequestRequest;
use App\Http\Resources\CreditRequestResource;
use App\Models\CreditRequest;
use App\Services\{CreditRequestService, CreditApprovalService};
use Illuminate\Http\{Request, JsonResponse};

class CreditRequestController extends Controller
{
    public function __construct(
        private CreditRequestService  $service,
        private CreditApprovalService $approvalService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $requests = CreditRequest::where('user_id', $request->user()->id)
            ->with('loan')->latest()->paginate(15);
        return CreditRequestResource::collection($requests)->response();
    }

    public function store(StoreCreditRequestRequest $request): JsonResponse
    {
        $req = $this->service->submit($request->user(), $request->validated());
        return response()->json(['data' => new CreditRequestResource($req)], 201);
    }

    public function show(CreditRequest $creditRequest): JsonResponse
    {
        $this->authorize('view', $creditRequest);
        return response()->json(['data' => new CreditRequestResource($creditRequest->load('loan','reviewer'))]);
    }

    // Admin: approve
    public function approve(Request $request, CreditRequest $creditRequest): JsonResponse
    {
        $this->authorize('approve', $creditRequest);
        $request->validate(['amount_approved' => 'required|numeric|min:1', 'interest_rate' => 'required|numeric|min:0']);
        $result = $this->approvalService->approve($creditRequest, $request->user(), $request->all());
        return response()->json(['data' => new CreditRequestResource($result)]);
    }

    // Admin: reject
    public function reject(Request $request, CreditRequest $creditRequest): JsonResponse
    {
        $this->authorize('reject', $creditRequest);
        $request->validate(['reason' => 'required|string|max:1000']);
        $result = $this->approvalService->reject($creditRequest, $request->user(), $request->reason);
        return response()->json(['data' => new CreditRequestResource($result)]);
    }
}
