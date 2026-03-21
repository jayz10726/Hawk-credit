<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\CreditRequest;
use Illuminate\Http\Request;

class CreditRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = CreditRequest::with(['user','organization'])
            ->when($request->status, fn($q,$s)=>$q->where('status',$s))
            ->when($request->org, fn($q,$o)=>$q->where('organization_id',$o))
            ->orderByDesc('created_at')->paginate(20);
        $orgs = \App\Models\Organization::pluck('name','id');
        $stats = [
            'total'       => CreditRequest::count(),
            'submitted'   => CreditRequest::where('status','submitted')->count(),
            'approved'    => CreditRequest::where('status','approved')->count(),
            'rejected'    => CreditRequest::where('status','rejected')->count(),
            'disbursed'   => CreditRequest::where('status','disbursed')->count(),
        ];
        return view('super.requests.index', compact('requests','orgs','stats'));
    }
}
