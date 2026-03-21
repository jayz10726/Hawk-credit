<?php
namespace App\Providers;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Use Tailwind CSS for pagination styling
       {
    Paginator::defaultView('vendor.pagination.tailwind');
     }


        // API rate limiter — 60 requests/min per user
        RateLimiter::for('api', fn(Request $r) =>
            Limit::perMinute(60)->by($r->user()?->id ?: $r->ip())
        );

        // Credit request limiter — 5 per hour per user
        RateLimiter::for('credit-requests', fn(Request $r) =>
            Limit::perHour(5)->by('cr_' . ($r->user()?->id ?: $r->ip()))
        );

        // Login attempts — 10 per minute per IP
        RateLimiter::for('login', fn(Request $r) =>
            Limit::perMinute(10)->by($r->ip())->response(fn() =>
                response()->json(['message' => 'Too many login attempts. Try again in 1 minute.'], 429)
            )
        );
    }
}
