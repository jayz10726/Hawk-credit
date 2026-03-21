@extends('layouts.app')
@section('title','Admin Dashboard')
@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold font-serif text-white">Admin Dashboard</h1>
        <p class="text-slate-500 text-sm mt-1 font-mono">{{ auth()->user()->organization?->name }}</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class='btn-primary'>+ Add Member</a>
</div>
{{-- KPI Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-mono text-slate-500 uppercase tracking-widest">Active Loans</span>
            <div class="w-7 h-7 rounded-lg bg-blue-900/30 flex items-center justify-center"><svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></div>
        </div>
        <p class="text-3xl font-bold font-mono text-white">{{ $stats['total_active_loans'] }}</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-mono text-slate-500 uppercase tracking-widest">Disbursed</span>
            <div class="w-7 h-7 rounded-lg bg-emerald-900/30 flex items-center justify-center"><svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2"/></svg></div>
        </div>
        <p class="text-3xl font-bold font-mono text-white">KES {{ number_format($stats['total_disbursed']/1000) }}K</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-mono text-slate-500 uppercase tracking-widest">Outstanding</span>
            <div class="w-7 h-7 rounded-lg bg-purple-900/30 flex items-center justify-center"><svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01"/></svg></div>
        </div>
        <p class="text-3xl font-bold font-mono text-white">KES {{ number_format($stats['total_outstanding']/1000) }}K</p>
 </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-mono text-slate-500 uppercase tracking-widest">Pending</span>
            <div class="w-7 h-7 rounded-lg bg-amber-900/30 flex items-center justify-center"><svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg></div>
        </div>
        <p class="text-3xl font-bold font-mono text-gold">{{ $stats['pending_requests'] }}</p>
        @if($stats['pending_requests'] > 0)
        <a href="{{ route('admin.requests.index', ['status'=>'submitted']) }}" class='text-xs font-mono text-amber-400 hover:text-amber-300 mt-1 block'>Review now →</a>
        @endif
    </div>
</div>
{{-- Pending Requests --}}
@if($pendingRequests->count() > 0)
<div class="card overflow-hidden mb-6">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/60">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold font-serif text-white">Pending Approvals</h2>
            <span class="badge-yellow">{{ $pendingRequests->count() }} pending</span>
        </div>
        <a href="{{ route('admin.requests.index') }}" class='text-xs font-mono text-blue-400 hover:text-blue-300'>View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="table-head"><tr>
                <th class="text-left px-6 py-3">Member</th>
                <th class="text-left px-6 py-3">Reference</th>
                <th class="text-right px-6 py-3">Amount</th>
                <th class="text-center px-6 py-3">Score</th>
                <th class="text-center px-6 py-3">Submitted</th>
                <th class="text-center px-6 py-3">Action</th>
            </tr></thead>
            <tbody>
                @foreach($pendingRequests as $req)
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-bold font-mono">{{ strtoupper(substr($req->user->first_name,0,1).substr($req->user->last_name,0,1)) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-white text-sm">{{ $req->user->full_name }}</p>
                                <p class="text-xs text-slate-500 font-mono">{{ $req->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $req->reference_code }}</td>
                    <td class="px-6 py-4 text-right font-mono font-semibold text-white">KES {{ number_format($req->amount_requested) }}</td>
                    <td class='px-6 py-4 text-center font-bold font-mono @if($req->score_at_application >= 660) text-emerald-400 @elseif($req->score_at_application >= 540) text-amber-400 @else text-red-400 @endif'>
                        {{ $req->score_at_application ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-center text-xs font-mono text-slate-500">{{ $req->created_at->diffForHumans() }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.requests.show', $req) }}" class='btn-primary text-xs px-3 py-1.5'>Review</a>
   </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
