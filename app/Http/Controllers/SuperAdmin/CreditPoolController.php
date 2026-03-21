<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class CreditPoolController extends Controller
{
    public function index()
    {
        $organizations = Organization::withCount('users')
            ->withSum('loans as total_disbursed','principal_amount')
            ->orderByDesc('credit_pool')->get();
        return view('super.credit-pools', compact('organizations'));
    }
    public function update(Request $request, Organization $org)
    {
        $request->validate([
            'credit_pool' => 'required|numeric|min:0',
            'action'      => 'required|in:set,add,subtract',
        ]);
        $amount = $request->credit_pool;
        if ($request->action === 'set') {
            $org->update(['credit_pool'=>$amount,'available_credit_pool'=>$amount]);
        } elseif ($request->action === 'add') {
            $org->increment('credit_pool',$amount);
            $org->increment('available_credit_pool',$amount);
        } else {
            $org->decrement('credit_pool',$amount);
            $org->decrement('available_credit_pool',$amount);
        }
        return back()->with('success','Credit pool updated for '.$org->name);
    }
}
