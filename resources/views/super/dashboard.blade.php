@extends('layouts.app')
@section('title','Global Dashboard')
@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold font-serif text-white">Global Dashboard</h1>
        <p class="text-slate-500 text-sm mt-1 font-mono">System-wide overview · All organizations</p>
    </div>
    <a href="{{ route('super.orgs.create') }}" class='btn-gold'>+ New Organization</a>
</div>
{{-- KPI Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card group">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-mono font-semibold text-slate-500 uppercase tracking-widest">Organizations</span>
            <div class="w-8 h-8 rounded-lg bg-blue-900/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold font-mono text-white">{{ $stats['total_orgs'] }}</p>
        <p class='text-xs text-emerald-400 mt-1 font-mono'>{{ $stats['active_orgs'] }} active</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-mono font-semibold text-slate-500 uppercase tracking-widest">Total Users</span>
            <div class="w-8 h-8 rounded-lg bg-purple-900/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold font-mono text-white">{{ $stats['total_users'] }}</p>
        <p class='text-xs text-slate-500 mt-1 font-mono'>across all orgs</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
         <span class="text-xs font-mono font-semibold text-slate-500 uppercase tracking-widest">Total Disbursed</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-900/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2M12 8V7m0 9v1"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold font-mono text-white">KES {{ number_format($stats['total_disbursed']/1000) }}K</p>
        <p class='text-xs text-slate-500 mt-1 font-mono'>principal disbursed</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-mono font-semibold text-slate-500 uppercase tracking-widest">Pending Review</span>
            <div class="w-8 h-8 rounded-lg bg-amber-900/30 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold font-mono text-white">{{ $stats['pending_requests'] }}</p>
        <p class='text-xs text-amber-400 mt-1 font-mono'>awaiting review</p>
    </div>
</div>
{{-- Organizations Table --}}
<div class="card overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800/60">
        <h2 class="font-semibold font-serif text-white">Organizations</h2>
        <a href="{{ route('super.orgs.index') }}" class='text-xs text-blue-400 hover:text-blue-300 font-mono transition-colors'>View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="table-head"><tr>
                <th class="text-left px-6 py-3">Organization</th>
                <th class="text-center px-6 py-3">Status</th>
                <th class="text-right px-6 py-3">Credit Pool</th>
                <th class="text-right px-6 py-3">Available</th>
                <th class="text-center px-6 py-3">Members</th>
                <th class="text-center px-6 py-3">Action</th>
            </tr></thead>
            <tbody>
                @forelse($organizations as $org)
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-bold">{{ strtoupper(substr($org->name,0,2)) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-white">{{ $org->name }}</p>
                                <p class="text-xs text-slate-500 font-mono">{{ $org->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class='px-6 py-4 text-center'>
                        <span class='@if($org->status==="active") badge-green @elseif($org->status==="suspended") badge-red @else badge-yellow @endif'>
                            {{ ucfirst($org->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-mono text-slate-300">KES {{ number_format($org->credit_pool) }}</td>
  <td class="px-6 py-4 text-right font-mono text-emerald-400 font-semibold">KES {{ number_format($org->available_credit_pool) }}</td>
                    <td class="px-6 py-4 text-center font-mono text-slate-300">{{ $org->users_count }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('super.orgs.show', $org) }}" class='text-xs px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700 transition-colors font-mono'>Manage</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-16 text-center text-slate-600 font-mono">
                    No organizations yet. <a href="{{ route('super.orgs.create') }}" class='text-gold hover:text-gold-light'>Create the first one →</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-800/40">{{ $organizations->links() }}</div>
</div>
@endsection
