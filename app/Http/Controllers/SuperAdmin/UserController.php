<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\{User, Organization};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('organization','roles')
            ->when($request->search, fn($q,$s) => $q->where('email','like',"%$s%")
                ->orWhere('first_name','like',"%$s%"))
            ->when($request->role, fn($q,$r) => $q->role($r))
            ->orderByDesc('created_at')->paginate(20);
        return view('super.users.index', compact('users'));
    }
    
    public function create()
{
    $organizations = \App\Models\Organization::where('status', 'active')->get();
    return view('super.users.create', compact('organizations'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'first_name'      => 'required|string|max:100',
        'last_name'       => 'required|string|max:100',
        'email'           => 'required|email|unique:users',
        'password'        => 'required|string|min:8',
        'role'            => 'required|in:super_admin,org_admin,user',
        'organization_id' => 'nullable|exists:organizations,id',
    ]);

    $user = \App\Models\User::create([
        'uuid'            => (string) \Illuminate\Support\Str::uuid(),
        'first_name'      => $data['first_name'],
        'last_name'       => $data['last_name'],
        'email'           => $data['email'],
        'password'        => \Illuminate\Support\Facades\Hash::make($data['password']),
        'organization_id' => $data['organization_id'] ?? null,
        'is_active'       => true,
    ]);

    $user->assignRole($data['role']);

      // Create starter credit score for non-admins
    if ($data['role'] === 'user') {
        \App\Models\CreditScore::create([
            'user_id'          => $user->id,
            'organization_id'  => $user->organization_id,
            'score'            => 300,
            'band'             => 'very_poor',
            'credit_limit'     => 0,
            'available_credit' => 0,
            'total_borrowed'   => 0,
            'total_repaid'     => 0,
            'on_time_payments' => 0,
            'late_payments'    => 0,
            'missed_payments'  => 0,
        ]);
    }

    return redirect()->route('super.users.index')
        ->with('success', "User {$user->full_name} created and assigned role {$data['role']}.");
}


    public function show(User $user)
    {
        $user->load(['organization','creditScore','creditRequests','loans']);
        return view('super.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role'      => 'required|in:super_admin,org_admin,user',
            'is_active' => 'boolean',
        ]);
        $user->syncRoles([$request->role]);
        $user->update(['is_active' => $request->boolean('is_active')]);
        return back()->with('success','User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();
    return redirect()->route('super.users.index')->with('success','User deleted.');
    }
}
