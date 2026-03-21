@extends('layouts.app')
@section('title','All Repayments')
@section('content')
<div class="mb-8"><h1 class="text-2xl font-bold font-serif text-white">All Repayments</h1>
    <p class="text-slate-500 text-sm mt-1 font-mono">Every payment recorded system-wide</p></div>
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="stat-card"><p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Total Collected</p>
        <p class="text-2xl font-bold font-mono text-emerald-400">KES {{ number_format($stats['total_amount']/1000) }}K</p></div>
 <div class="stat-card"><p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Total Transactions</p>
        <p class="text-2xl font-bold font-mono text-white">{{ number_format($stats['total_count']) }}</p></div>
    <div class="stat-card"><p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">This Month</p>
        <p class="text-2xl font-bold font-mono text-gold">KES {{ number_format($stats['this_month']/1000) }}K</p></div>
</div>
<div class="card overflow-hidden">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead class="table-head"><tr>
            <th class="text-left px-5 py-3">Reference</th><th class="text-left px-5 py-3">Member</th>
            <th class="text-left px-5 py-3">Organization</th><th class="text-right px-5 py-3">Amount</th>
            <th class="text-center px-5 py-3">Method</th><th class="text-center px-5 py-3">Date</th>
        </tr></thead>
        <tbody>
            @forelse($repayments as $rep)
            <tr class="table-row">
                <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ $rep->reference_code }}</td>
                <td class="px-5 py-3"><p class="text-white font-medium text-sm">{{ $rep->user->full_name }}</p></td>
                <td class="px-5 py-3 text-xs text-slate-400 font-mono">{{ $rep->organization->name }}</td>
                <td class="px-5 py-3 text-right font-mono font-bold text-emerald-400">KES {{ number_format($rep->amount) }}</td>
                <td class='px-5 py-3 text-center'><span class='badge-blue capitalize'>{{ str_replace('_',' ',$rep->payment_method) }}</span></td>
                <td class="px-5 py-3 text-center text-xs font-mono text-slate-500">{{ $rep->paid_at?->format("M j, Y H:i") }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-16 text-center text-slate-600 font-mono">No repayments yet.</td></tr>
            @endforelse
        </tbody></table></div>
    <div class="px-5 py-4 border-t border-slate-800/40">{{ $repayments->links() }}</div>
</div>
@endsection
