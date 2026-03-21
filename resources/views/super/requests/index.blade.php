@extends('layouts.app')
@section('title','All Credit Requests')
@section('content')
<div class="mb-8"><h1 class="text-2xl font-bold font-serif text-white">All Credit Requests</h1>
    <p class="text-slate-500 text-sm mt-1 font-mono">Every application across all organizations</p></div>
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    @foreach(['total'=>'text-white','submitted'=>'text-amber-400','approved'=>'text-emerald-400','rejected'=>'text-red-400','disbursed'=>'text-blue-400'] as $key=>$col)
    <div class="stat-card"><p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-2">{{ ucfirst($key) }}</p>
        <p class="text-2xl font-bold font-mono {{ $col }}">{{ $stats[$key] }}</p></div>
    @endforeach
</div>
<form method="GET" class="flex gap-3 mb-6 flex-wrap">
    <select name="status" class="input w-44">
        <option value="">All Status</option>
        @foreach(['submitted','under_review','approved','rejected','disbursed'] as $s)
        <option value='{{ $s }}' @selected(request('status')==$s)>{{ ucwords(str_replace('_',' ',$s)) }}</option>
        @endforeach
    </select>
    <select name="org" class="input w-48">
  <option value="">All Organizations</option>
        @foreach($orgs as $id=>$name)<option value='{{ $id }}' @selected(request('org')==$id)>{{ $name }}</option>@endforeach
    </select>
    <button class="btn-primary">Filter</button>
    <a href="{{ route('super.requests.index') }}" class='btn-ghost'>Clear</a>
</form>
<div class="card overflow-hidden">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead class="table-head"><tr>
            <th class="text-left px-5 py-3">Reference</th><th class="text-left px-5 py-3">Member</th>
            <th class="text-left px-5 py-3">Organization</th><th class="text-right px-5 py-3">Amount</th>
            <th class="text-center px-5 py-3">Score</th><th class="text-center px-5 py-3">Status</th>
            <th class="text-center px-5 py-3">Date</th>
        </tr></thead>
        <tbody>
            @forelse($requests as $req)
            <tr class="table-row">
                <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ $req->reference_code }}</td>
                <td class="px-5 py-3"><p class="text-white font-medium text-sm">{{ $req->user->full_name }}</p><p class="text-xs text-slate-500 font-mono">{{ $req->user->email }}</p></td>
                <td class="px-5 py-3 text-xs text-slate-400 font-mono">{{ $req->organization->name }}</td>
                <td class="px-5 py-3 text-right font-mono font-semibold text-white">KES {{ number_format($req->amount_requested) }}</td>
                <td class='px-5 py-3 text-center font-bold font-mono @if($req->score_at_application >= 660) text-emerald-400 @elseif($req->score_at_application >= 540) text-amber-400 @else text-red-400 @endif'>{{ $req->score_at_application ?? '—' }}</td>
                <td class='px-5 py-3 text-center'><span class='@switch($req->status) @case("submitted") badge-yellow @break @case("approved") @case("disbursed") badge-green @break @case("rejected") badge-red @break @default badge-blue @endswitch'>{{ ucwords(str_replace('_',' ',$req->status)) }}</span></td>
                <td class="px-5 py-3 text-center text-xs font-mono text-slate-500">{{ $req->created_at->format("M j, Y") }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-16 text-center text-slate-600 font-mono">No requests found.</td></tr>
            @endforelse
        </tbody></table></div>
    <div class="px-5 py-4 border-t border-slate-800/40">{{ $requests->links() }}</div>
</div>
@endsection
