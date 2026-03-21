<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\{Loan,User,Repayment};
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    { return view('super.reports'); }

    public function exportLoans(): StreamedResponse
    {
        $loans = Loan::with(['user','organization'])->get();
        return response()->streamDownload(function() use ($loans) {
            $out = fopen("php://output","w");
            fputcsv($out,['Reference','User','Email','Organization','Principal','Rate','Outstanding','Status','Start Date']);
            foreach($loans as $l) {
                fputcsv($out,[$l->reference_code,$l->user->full_name,$l->user->email,
                    $l->organization->name,$l->principal_amount,$l->interest_rate.'%',
                    $l->outstanding_balance,$l->status,$l->start_date]);
            }
            fclose($out);
        }, 'hawks-loans-'.date('Y-m-d').'.csv');
    }

    public function exportUsers(): StreamedResponse
    {
        $users = User::with(['organization','roles','creditScore'])->get();
        return response()->streamDownload(function() use ($users) {
            $out = fopen("php://output","w");
            fputcsv($out,['Name','Email','Phone','Organization','Role','Score','Credit Limit','Status','Joined']);
            foreach($users as $u) {
                fputcsv($out,[$u->full_name,$u->email,$u->phone,
                    $u->organization?->name ?? 'System',$u->roles->first()?->name,
                    $u->creditScore?->score ?? 300,$u->creditScore?->credit_limit ?? 0,
                    $u->is_active ? 'Active':'Suspended',$u->created_at->format('Y-m-d')]);
            }
            fclose($out);
        }, 'hawks-users-'.date('Y-m-d').'.csv');
    }
public function exportRepayments(): StreamedResponse
    {
        $repayments = Repayment::with(['user','loan','organization'])
            ->where('status','confirmed')->get();
        return response()->streamDownload(function() use ($repayments) {
            $out = fopen("php://output","w");
            fputcsv($out,['Reference','User','Loan Ref','Organization','Amount','Method','Paid At']);
            foreach($repayments as $r) {
                fputcsv($out,[$r->reference_code,$r->user->full_name,
                    $r->loan->reference_code,$r->organization->name,
                    $r->amount,$r->payment_method,$r->paid_at]);
            }
            fclose($out);
        }, 'hawks-repayments-'.date('Y-m-d').'.csv');
    }
}
