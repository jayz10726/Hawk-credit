<?php
namespace App\Http\Controllers\OrgAdmin;
use App\Http\Controllers\Controller;
use App\Models\{User, CreditScore, Organization};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $orgId = auth()->user()->organization_id;
        $users = User::with('creditScore','roles')
            ->where('organization_id',$orgId)
            ->when($request->search, fn($q,$s) =>
                $q->where('first_name','like',"%$s%")->orWhere('email','like',"%$s%"))
            ->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    { return view('admin.users.create'); }

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
        $data['uuid']            = (string) \Illuminate\Support\Str::uuid();
        $data['organization_id'] = auth()->user()->organization_id;
        $data['password']        = Hash::make($data['password']);
        $data['is_active']       = true;

        $user = User::create($data);
        $user->assignRole('user');
        // Create starter credit score
        CreditScore::create([
            'user_id'         => $user->id,
            'organization_id' => $user->organization_id,
            'score'           => 300,
            'band'            => 'very_poor',
            'credit_limit'    => 0,
            'available_credit'=> 0,
        ]);
        return redirect()->route('admin.users.index')->with('success','Member added.');
    }

    public function show(User $user)
    {
        $user->load(['creditScore','creditRequests','loans']);
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
            $user->creditScore()->update([
 'credit_limit'     => $request->credit_limit,
                'available_credit' => $request->credit_limit - $user->creditScore->total_borrowed + $user->creditScore->total_repaid,
            ]);
        }
        return back()->with('success','User updated.');
    }
}
