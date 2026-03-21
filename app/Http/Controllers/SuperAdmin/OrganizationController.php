<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\{Organization, User};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::withCount('users')
            ->orderByDesc('created_at')->paginate(15);
        return view('super.orgs.index', compact('organizations'));
    }

    public function create()
    { return view('super.orgs.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:organizations',
            'phone'             => 'nullable|string|max:20',
            'address'           => 'nullable|string',
            'subscription_tier' => 'required|in:basic,professional,enterprise',
            'credit_pool'       => 'required|numeric|min:0',
        ]);

        $data['uuid']                 = Str::uuid();
        $data['slug']                 = Str::slug($data['name']).'-'.Str::random(4);
        $data['status']               = 'active';
        $data['available_credit_pool']= $data['credit_pool'];
        $data['created_by']           = auth()->id();

        $org = Organization::create($data);

        return redirect()->route('super.orgs.show', $org)
            ->with('success', 'Organization created successfully.');
    }

    public function show(Organization $org)
    {
        $org->load(['users','loans','creditRequests']);
        $stats = [
            'total_users'     => $org->users()->count(),
            'active_loans'    => $org->loans()->where('status','active')->count(),
            'total_disbursed' => $org->loans()->sum('principal_amount'),
            'pending_requests'=> $org->creditRequests()->where('status','submitted')->count(),
        ];
        return view('super.orgs.show', compact('org','stats'));
    }

    public function edit(Organization $org)
    { return view('super.orgs.edit', compact('org')); }

    public function update(Request $request, Organization $org)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:organizations,email,'.$org->id,
            'phone'             => 'nullable|string',
            'status'            => 'required|in:active,suspended,pending',
  'subscription_tier' => 'required|in:basic,professional,enterprise',
            'credit_pool'       => 'required|numeric|min:0',
        ]);
        $org->update($data);
        return redirect()->route('super.orgs.show', $org)->with('success','Updated.');
    }

    public function destroy(Organization $org)
    {
        $org->delete();
        return redirect()->route('super.orgs.index')->with('success','Organization deleted.');
    }
}
