<?php
namespace App\Http\Controllers\UserCtrl;
use App\Http\Controllers\Controller;
use App\Models\CreditScoreHistory;
use Illuminate\Http\Request;

class CreditScoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $user    = auth()->user();
        $score   = $user->creditScore;
        $history = CreditScoreHistory::where('user_id',$user->id)
            ->orderBy('created_at')->limit(24)->get();
        return view('user.score', compact('score','history'));
    }
}
