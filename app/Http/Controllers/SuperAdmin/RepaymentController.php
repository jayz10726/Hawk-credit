<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Repayment;
use Illuminate\Http\Request;

class RepaymentController extends Controller
{
    public function index(Request $request)
    {
        $repayments = Repayment::with(['user','loan','organization'])
            ->when($request->method, fn($q,$m)=>$q->where('payment_method',$m))
            ->when($request->org, fn($q,$o)=>$q->where('organization_id',$o))
            ->orderByDesc('paid_at')->paginate(25);
        $orgs = \App\Models\Organization::pluck('name','id');
        $stats = [
            'total_amount'  => Repayment::where('status','confirmed')->sum('amount'),
            'total_count'   => Repayment::where('status','confirmed')->count(),
            'this_month'    => Repayment::where('status','confirmed')
                ->whereMonth('paid_at', now()->month)->sum('amount'),
        ];
        return view('super.repayments.index', compact('repayments','orgs','stats'));
    }
}
