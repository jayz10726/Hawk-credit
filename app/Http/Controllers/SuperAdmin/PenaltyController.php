<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class PenaltyController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user','organization'])
            ->where('penalty_balance','>',0)
            ->orderByDesc('penalty_balance')->paginate(20);
        $totalPenalties = Loan::sum('penalty_balance');
        return view('super.penalties.index', compact('loans','totalPenalties'));
    }
    public function waive(Request $request, Loan $loan)
  {
        $request->validate([
            'reason' => 'required|string|min:5',
        ]);
        $loan->update(['penalty_balance'=>0]);
        return back()->with('success','Penalty waived for loan '.$loan->reference_code);
    }
}
