<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::with(['user'])
            ->when($request->event, fn($q,$e)=>$q->where('event','like',"%$e%"))
            ->when($request->user_id, fn($q,$u)=>$q->where('user_id',$u))
            ->orderByDesc('created_at')->paginate(30);
        return view('super.audit-logs', compact('logs'));
    }
}
