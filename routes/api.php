<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\v1 as API;

Route::prefix('v1')->group(function () {

    // ── Public auth routes ──────────────────────────────────
    Route::post('auth/login',    [API\AuthController::class, 'login']);
    Route::post('auth/register', [API\AuthController::class, 'register']);

    // ── Authenticated routes ────────────────────────────────
    Route::middleware(['auth:sanctum', 'active'])->group(function () {

        Route::post('auth/logout', [API\AuthController::class, 'logout']);
        Route::get('me',          [API\AuthController::class, 'me']);
        Route::get('me/score',    [API\CreditScoreController::class, 'show']);

        // ── Regular user endpoints ─────────────────────────
        Route::middleware('role:user')->group(function () {
            Route::apiResource('credit-requests', API\CreditRequestController::class)->only(['index','store','show']);
            Route::apiResource('loans',           API\LoanController::class)->only(['index','show']);
            Route::post('loans/{loan}/repayments', [API\RepaymentController::class, 'store']);
            Route::get('loans/{loan}/repayments',  [API\RepaymentController::class, 'index']);
            Route::get('notifications',            [API\NotificationController::class, 'index']);
            Route::patch('notifications/{id}/read',[API\NotificationController::class, 'markRead']);
        });

        // ── Org Admin endpoints ────────────────────────────
        Route::middleware('role:org_admin')->prefix('admin')->group(function () {
            Route::get('credit-requests',                 [API\CreditRequestController::class, 'adminIndex']);
            Route::post('credit-requests/{req}/approve',  [API\CreditRequestController::class, 'approve']);
            Route::post('credit-requests/{req}/reject',   [API\CreditRequestController::class, 'reject']);
            Route::get('users',                           [API\UserController::class, 'index']);
            Route::put('users/{user}/credit-limit',       [API\UserController::class, 'updateLimit']);
           Route::get('analytics',                       [API\AnalyticsController::class, 'org']);
            Route::post('repayments/{repayment}/confirm', [API\RepaymentController::class, 'confirm']);
        });

        // ── Super Admin endpoints ──────────────────────────
        Route::middleware('role:super_admin')->prefix('super')->group(function () {
            Route::apiResource('organizations', API\OrganizationController::class);
            Route::get('analytics',             [API\AnalyticsController::class, 'global']);
            Route::get('audit-logs',            [API\AuditLogController::class, 'index']);
            Route::apiResource('users',         API\UserController::class);
        });
    });
});
