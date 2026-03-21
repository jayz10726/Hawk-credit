<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request;

class EnsureOrgIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && $user->organization && $user->organization->status !== 'active') {
            abort(403, 'Your organization account is suspended.');
        }
        return $next($request);
    }
}

