<?php

namespace App\Http\Controllers\OrgAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CreditScore;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $orgId = auth()->user()->organization_id;
        $users = User::with('creditScore', 'roles')
            ->where('organization_id', $orgId)
            ->when($request->search, fn($q, $s) =>
                $q->where('first_name', 'like', "%$s%")
                  ->orWhere('last_name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%"))
            ->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|unique:users',
            'phone'          => 'nullable|string|max:20',
            'national_id'    => 'nullable|string|unique:users',
            'monthly_income' => 'nullable|numeric|min:0',
            'password'       => 'required|string|min:8',
        ]);

        $user = User::create([
            'uuid'              => (string) Str::uuid(),
            'first_name'        => $data['first_name'],
            'last_name'         => $data['last_name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'national_id'       => $data['national_id'] ?? null,
            'monthly_income'    => $data['monthly_income'] ?? null,
            'employment_status' => 'employed',
            'organization_id'   => auth()->user()->organization_id,
            'password'          => Hash::make($data['password']),
            'is_active'         => true,
        ]);

        $user->assignRole('user');

        // Always create a starter credit score
        CreditScore::create([
            'user_id'          => $user->id,
            'organization_id'  => $user->organization_id,
            'score'            => 540,
            'band'             => 'poor',
            'risk_category'    => 'medium',
            'credit_limit'     => 0,
            'available_credit' => 0,
            'total_borrowed'   => 0,
            'total_repaid'     => 0,
            'on_time_payments' => 0,
            'late_payments'    => 0,
            'missed_payments'  => 0,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Member {$user->first_name} {$user->last_name} added successfully. They can log in with: {$data['email']} / {$data['password']}");
    }

    public function show(User $user)
    {
        $user->load(['creditScore', 'creditRequests', 'loans']);
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'credit_limit' => 'nullable|numeric|min:0',
            'is_active'    => 'boolean',
        ]);

        $user->update(['is_active' => $request->boolean('is_active')]);

        if ($request->filled('credit_limit')) {
            $score = $user->creditScore;

            if (!$score) {
                // User has no credit score yet — create one now
                CreditScore::create([
                    'user_id'          => $user->id,
                    'organization_id'  => $user->organization_id,
                    'score'            => 540,
                    'band'             => 'poor',
                    'risk_category'    => 'medium',
                    'credit_limit'     => $request->credit_limit,
                    'available_credit' => $request->credit_limit,
                    'total_borrowed'   => 0,
                    'total_repaid'     => 0,
                    'on_time_payments' => 0,
                    'late_payments'    => 0,
                    'missed_payments'  => 0,
                ]);
            } else {
                // Update existing credit score
                $used      = max(0, $score->total_borrowed - $score->total_repaid);
                $available = max(0, $request->credit_limit - $used);
                $score->update([
                    'credit_limit'     => $request->credit_limit,
                    'available_credit' => $available,
                ]);
            }
        }

        return back()->with('success', 'Member updated successfully.');
    }
}