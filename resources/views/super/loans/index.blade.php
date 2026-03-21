@extends('layouts.app')
@section('title','All Loans')
@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold font-serif text-white">All Loans</h1>
    <p class="text-slate-500 text-sm mt-1 font-mono">System-wide loan portfolio</p>
</div>
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="stat-card"><p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Total Disbursed</p>
        <p class="text-2xl font-bold font-mono text-white">KES {{ number_format($stats['disbursed']/1000) }}K</p></div>
    <div class="stat-card"><p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Outstanding</p>
        <p class="text-2xl font-bold font-mono text-blue-400">KES {{ number_format($stats['outstanding']/1000) }}K</p></div>
    <div class="stat-card"><p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Active / Completed / Defaulted</p>
        <p class="text-2xl font-bold font-mono text-white">{{ $stats['active'] }} / {{ $stats['completed'] }} / <span class="text-red-400">{{ $stats['defaulted'] }}</span></p></div>
</div>
<form method="GET" class="flex gap-3 mb-6 flex-wrap">
    <select name="status" class="input w-40">
        <option value="">All Status</option>
        @foreach(['active','completed','defaulted','written_off'] as $s)
        <option value='{{ $s }}' @selected(request('status')==$s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <select name="org" class="input w-48">
        <option value="">All Organizations</option>
        @foreach($orgs as $id=>$name)
        <option value='{{ $id }}' @selected(request('org')==$id)>{{ $name }}</option>
        @endforeach
    </select>
    <button class="btn-primary">Filter</button>
    <a href="{{ route('super.loans.index') }}" class='btn-ghost'>Clear</a>
</form>
<div class="card overflow-hidden">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead class="table-head"><tr>
            <th class="text-left px-5 py-3">Reference</th>
            <th class="text-left px-5 py-3">Member</th>
            <th class="text-left px-5 py-3">Organization</th>
<th class="text-right px-5 py-3">Principal</th>
            <th class="text-right px-5 py-3">Outstanding</th>
            <th class="text-center px-5 py-3">Status</th>
            <th class="text-center px-5 py-3">Action</th>
        </tr></thead>
        <tbody>
            @forelse($loans as $loan)
            <tr class="table-row">
                <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ $loan->reference_code }}</td>
                <td class="px-5 py-3"><p class="text-white font-medium">{{ $loan->user->full_name }}</p><p class="text-xs text-slate-500 font-mono">{{ $loan->user->email }}</p></td>
                <td class="px-5 py-3 text-slate-400 text-xs font-mono">{{ $loan->organization->name }}</td>
                <td class="px-5 py-3 text-right font-mono text-slate-300">KES {{ number_format($loan->principal_amount) }}</td>
                <td class="px-5 py-3 text-right font-mono text-blue-400 font-semibold">KES {{ number_format($loan->outstanding_balance) }}</td>
                <td class='px-5 py-3 text-center'><span class='@if($loan->status==="active") badge-green @elseif($loan->status==="completed") badge-blue @else badge-red @endif'>{{ ucfirst($loan->status) }}</span></td>
                <td class='px-5 py-3 text-center'><a href="{{ route('super.loans.show',$loan) }}" class='text-xs px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700 font-mono'>View</a></td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-16 text-center text-slate-600 font-mono">No loans found.</td></tr>
            @endforelse
        </tbody></table></div>
    <div class="px-5 py-4 border-t border-slate-800/40">{{ $loans->links() }}</div>
</div>
@endsection
