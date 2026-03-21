@extends('layouts.app')
@section('title', $user->full_name)
@section('content')
<div class="flex items-center justify-between mb-8">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center ring-1 ring-gold/20">
            <span class="text-white text-lg font-bold">{{ strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) }}</span>
        </div>
        <div>
            <h1 class="text-2xl font-bold font-serif text-white">{{ $user->full_name }}</h1>
            <p class="text-slate-500 text-sm font-mono">{{ $user->email }}</p>
 </div>
    </div>
    <a href="{{ route('admin.users.index') }}" class='btn-ghost'>← Back</a>
</div>
<div class="grid lg:grid-cols-3 gap-6 mb-8">
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-4">Credit Profile</h2>
        <div class='text-center mb-4'>
            <p class='text-5xl font-bold font-mono @if($user->creditScore?->score >= 660) text-emerald-400 @elseif($user->creditScore?->score >= 540) text-amber-400 @else text-red-400 @endif'>
                {{ $user->creditScore?->score ?? 300 }}
            </p>
            <p class="text-xs font-mono text-slate-500">/ 850 — {{ ucfirst($user->creditScore?->band ?? 'very_poor') }}</p>
        </div>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-slate-500">Credit Limit</span><span class="font-mono font-semibold text-white">KES {{ number_format($user->creditScore?->credit_limit ?? 0) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Available</span><span class="font-mono font-semibold text-emerald-400">KES {{ number_format($user->creditScore?->available_credit ?? 0) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">On-time Payments</span><span class="font-mono text-emerald-400">{{ $user->creditScore?->on_time_payments ?? 0 }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Late Payments</span><span class="font-mono text-amber-400">{{ $user->creditScore?->late_payments ?? 0 }}</span></div>
        </div>
    </div>
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-4">Personal Info</h2>
        <div class="space-y-3 text-sm">
            <div><span class="text-slate-500 block text-xs font-mono uppercase tracking-widest mb-0.5">Phone</span><span class="text-slate-300">{{ $user->phone ?? "—" }}</span></div>
            <div><span class="text-slate-500 block text-xs font-mono uppercase tracking-widest mb-0.5">National ID</span><span class="text-slate-300">{{ $user->national_id ?? "—" }}</span></div>
            <div><span class="text-slate-500 block text-xs font-mono uppercase tracking-widest mb-0.5">Employment</span><span class="text-slate-300 capitalize">{{ $user->employment_status }}</span></div>
            <div><span class="text-slate-500 block text-xs font-mono uppercase tracking-widest mb-0.5">Monthly Income</span><span class="text-slate-300">KES {{ number_format($user->monthly_income ?? 0) }}</span></div>
            <div><span class="text-slate-500 block text-xs font-mono uppercase tracking-widest mb-0.5">Status</span>
                <span class='@if($user->is_active) badge-green @else badge-red @endif'>{{ $user->is_active ? 'Active' : 'Suspended' }}</span>
            </div>
        </div>
    </div>
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-4">Manage Member</h2>
        <form method='POST' action="{{ route('admin.users.update', $user) }}" class='space-y-4'>
            @csrf @method('PUT')
            <div>
                <label class="label">Credit Limit (KES)</label>
                <input type='number' name='credit_limit' value='{{ $user->creditScore?->credit_limit ?? 0 }}' min='0' class='input'>
            </div>
            <div class="flex items-center gap-3">
                <input type='checkbox' name='is_active' id='is_active' value='1' @checked($user->is_active)
                       class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-600 focus:ring-blue-500">
                <label for='is_active' class='text-sm text-slate-300'>Account Active</label>
            </div>
            <button type="submit" class="w-full btn-primary">Save Changes</button>
   </form>
    </div>
</div>
@if($user->loans->count() > 0)
<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-800/60"><h2 class="font-semibold font-serif text-white">Loan History</h2></div>
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead class="table-head"><tr>
            <th class="text-left px-5 py-3">Reference</th>
            <th class="text-right px-5 py-3">Amount</th>
            <th class="text-right px-5 py-3">Outstanding</th>
            <th class="text-center px-5 py-3">Status</th>
        </tr></thead>
        <tbody>
            @foreach($user->loans as $loan)
            <tr class="table-row">
                <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ $loan->reference_code }}</td>
                <td class="px-5 py-3 text-right font-mono text-white">KES {{ number_format($loan->principal_amount) }}</td>
                <td class="px-5 py-3 text-right font-mono text-blue-400">KES {{ number_format($loan->outstanding_balance) }}</td>
                <td class='px-5 py-3 text-center'><span class='@if($loan->status==="active") badge-green @elseif($loan->status==="completed") badge-blue @else badge-red @endif'>{{ ucfirst($loan->status) }}</span></td>
            </tr>
            @endforeach
        </tbody></table></div>
</div>
@endif
@endsection
