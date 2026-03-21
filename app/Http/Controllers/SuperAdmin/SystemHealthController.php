<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\{User,Organization,Loan,CreditRequest,Repayment,AuditLog};
use Illuminate\Support\Facades\DB;

class SystemHealthController extends Controller
{
    public function index()
    {
        $health = [
            'db_size'         => $this->getDbSize(),
            'total_records'   => [
                'users'        => User::count(),
                'organizations'=> Organization::count(),
                'loans'        => Loan::count(),
                'requests'     => CreditRequest::count(),
                'repayments'   => Repayment::count(),
                'audit_logs'   => AuditLog::count(),
            ],
            'recent_activity' => AuditLog::with('user')
                ->orderByDesc('created_at')->limit(10)->get(),
            'php_version'     => PHP_VERSION,
'laravel_version' => app()->version(),
            'environment'     => app()->environment(),
            'uptime'          => now()->toDateTimeString(),
        ];
        return view('super.system-health', compact('health'));
    }
    private function getDbSize(): string
    {
        try {
            $path = database_path('database.sqlite');
            return file_exists($path) ? round(filesize($path)/1024/1024,2).' MB' : 'N/A';
        } catch (\Exception $e) { return "N/A"; }
    }
}
