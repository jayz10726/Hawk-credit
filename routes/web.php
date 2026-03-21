<?php

use Illuminate\Support\Facades\Route;

// ── Public ──────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── After Login: Role-based redirect ────────────────────────────
Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('super_admin')) {
        return redirect()->route('super.dashboard');
    } elseif (auth()->user()->hasRole('org_admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'active'])->name('dashboard');

// ── Auth routes (Breeze) ─────────────────────────────────────────
require __DIR__.'/auth.php';

// ════════════════════════════════════════════════════════════════
// SUPER ADMIN
// ════════════════════════════════════════════════════════════════
Route::prefix('super-admin')
    ->name('super.')
    ->middleware(['web', 'auth', 'active', 'role:super_admin'])
    ->group(function () {

    Route::get('dashboard', \App\Http\Controllers\SuperAdmin\DashboardController::class)
        ->name('dashboard');

    Route::resource('orgs', \App\Http\Controllers\SuperAdmin\OrganizationController::class)
        ->names('orgs');

    // Users — create MUST come before {user}
    Route::get('users/create', [\App\Http\Controllers\SuperAdmin\UserController::class, 'create'])
        ->name('users.create');
    Route::post('users', [\App\Http\Controllers\SuperAdmin\UserController::class, 'store'])
        ->name('users.store');
    Route::get('users', [\App\Http\Controllers\SuperAdmin\UserController::class, 'index'])
        ->name('users.index');
    Route::get('users/{user}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'show'])
        ->name('users.show');
    Route::put('users/{user}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'update'])
        ->name('users.update');
    Route::delete('users/{user}', [\App\Http\Controllers\SuperAdmin\UserController::class, 'destroy'])
        ->name('users.destroy');

    Route::get('analytics', \App\Http\Controllers\SuperAdmin\AnalyticsController::class)
        ->name('analytics');

    // ── Credit Management ────────────────────────────────────────
    Route::get('loans', [\App\Http\Controllers\SuperAdmin\LoanController::class, 'index'])
        ->name('loans.index');
    Route::get('loans/{loan}', [\App\Http\Controllers\SuperAdmin\LoanController::class, 'show'])
        ->name('loans.show');
    Route::get('requests', [\App\Http\Controllers\SuperAdmin\CreditRequestController::class, 'index'])
        ->name('requests.index');
    Route::get('repayments', [\App\Http\Controllers\SuperAdmin\RepaymentController::class, 'index'])
        ->name('repayments.index');

    // ── Financial ────────────────────────────────────────────────
    Route::get('credit-pools', [\App\Http\Controllers\SuperAdmin\CreditPoolController::class, 'index'])
        ->name('credit-pools');
    Route::post('credit-pools/{org}', [\App\Http\Controllers\SuperAdmin\CreditPoolController::class, 'update'])
        ->name('credit-pools.update');
    Route::get('penalties', [\App\Http\Controllers\SuperAdmin\PenaltyController::class, 'index'])
        ->name('penalties.index');
    Route::post('penalties/{loan}/waive', [\App\Http\Controllers\SuperAdmin\PenaltyController::class, 'waive'])
        ->name('penalties.waive');

    // ── Settings ─────────────────────────────────────────────────
    Route::get('audit-logs', [\App\Http\Controllers\SuperAdmin\AuditLogController::class, 'index'])
        ->name('audit-logs');
    Route::get('system-health', [\App\Http\Controllers\SuperAdmin\SystemHealthController::class, 'index'])
        ->name('system-health');

    // ── Reports ──────────────────────────────────────────────────
    Route::get('reports', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'index'])
        ->name('reports.loans');
    Route::get('reports/export/loans', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'exportLoans'])
        ->name('reports.export.loans');
    Route::get('reports/export/users', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'exportUsers'])
        ->name('reports.export.users');
    Route::get('reports/export/repayments', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'exportRepayments'])
        ->name('reports.export.repayments');
});

// ════════════════════════════════════════════════════════════════
// ORG ADMIN
// ════════════════════════════════════════════════════════════════
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['web', 'auth', 'active', 'role:org_admin'])
    ->group(function () {

    Route::get('dashboard', \App\Http\Controllers\OrgAdmin\DashboardController::class)
        ->name('dashboard');

    Route::get('requests', [\App\Http\Controllers\OrgAdmin\CreditRequestController::class, 'index'])
        ->name('requests.index');
    Route::get('requests/{request}', [\App\Http\Controllers\OrgAdmin\CreditRequestController::class, 'show'])
        ->name('requests.show');
    Route::post('requests/{req}/approve', [\App\Http\Controllers\OrgAdmin\CreditRequestController::class, 'approve'])
        ->name('requests.approve');
    Route::post('requests/{req}/reject', [\App\Http\Controllers\OrgAdmin\CreditRequestController::class, 'reject'])
        ->name('requests.reject');

    Route::get('loans', [\App\Http\Controllers\OrgAdmin\LoanController::class, 'index'])
        ->name('loans.index');
    Route::get('loans/{loan}', [\App\Http\Controllers\OrgAdmin\LoanController::class, 'show'])
        ->name('loans.show');

    // Users — create MUST come before {user}
    Route::get('users/create', [\App\Http\Controllers\OrgAdmin\UserController::class, 'create'])
        ->name('users.create');
    Route::post('users', [\App\Http\Controllers\OrgAdmin\UserController::class, 'store'])
        ->name('users.store');
    Route::get('users', [\App\Http\Controllers\OrgAdmin\UserController::class, 'index'])
        ->name('users.index');
    Route::get('users/{user}', [\App\Http\Controllers\OrgAdmin\UserController::class, 'show'])
        ->name('users.show');
    Route::put('users/{user}', [\App\Http\Controllers\OrgAdmin\UserController::class, 'update'])
        ->name('users.update');

    Route::get('analytics', \App\Http\Controllers\OrgAdmin\AnalyticsController::class)
        ->name('analytics');
});

// ════════════════════════════════════════════════════════════════
// NORMAL USER
// ════════════════════════════════════════════════════════════════
Route::prefix('my')
    ->name('user.')
    ->middleware(['web', 'auth', 'active', 'role:user'])
    ->group(function () {

    Route::get('dashboard', \App\Http\Controllers\UserCtrl\DashboardController::class)
        ->name('dashboard');

    Route::get('applications/create', [\App\Http\Controllers\UserCtrl\CreditRequestController::class, 'create'])
        ->name('requests.create');
    Route::post('applications', [\App\Http\Controllers\UserCtrl\CreditRequestController::class, 'store'])
        ->name('requests.store');
    Route::get('applications', [\App\Http\Controllers\UserCtrl\CreditRequestController::class, 'index'])
        ->name('requests.index');
    Route::get('applications/{request}', [\App\Http\Controllers\UserCtrl\CreditRequestController::class, 'show'])
        ->name('requests.show');

    Route::get('loans', [\App\Http\Controllers\UserCtrl\LoanController::class, 'index'])
        ->name('loans.index');
    Route::get('loans/{loan}', [\App\Http\Controllers\UserCtrl\LoanController::class, 'show'])
        ->name('loans.show');
    Route::post('loans/{loan}/pay', [\App\Http\Controllers\UserCtrl\RepaymentController::class, 'store'])
        ->name('loans.pay');

    Route::get('score', \App\Http\Controllers\UserCtrl\CreditScoreController::class)
        ->name('score');
});