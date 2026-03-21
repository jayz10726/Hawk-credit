<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use App\Models\{CreditRequest, Loan, Repayment, LoanInstallment};

class ScopeTenantData
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Super admins bypass tenant scoping
        if (!$user || $user->hasRole('super_admin')) {
            return $next($request);
        }

        $orgId = $user->organization_id;

        // Auto-scope all queries for these models
        $models = [CreditRequest::class, Loan::class, Repayment::class, LoanInstallment::class];
        foreach ($models as $model) {
            $model::addGlobalScope('tenant', fn($q) => $q->where('organization_id', $orgId));
        }

        return $next($request);
    }
}

