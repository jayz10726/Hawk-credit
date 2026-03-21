@extends('layouts.app')
@section('title','Organizations')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold font-serif text-white">Organizations</h1>
        <p class="text-slate-500 text-sm mt-1 font-mono">All registered organizations on Hawks Credits</p>
    </div>
    <a href="{{ route('super.orgs.create') }}" class="btn-gold">+ New Organization</a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="table-head">
                <tr>
                    <th class="text-left px-6 py-3">Organization</th>
                    <th class="text-center px-6 py-3">Status</th>
                    <th class="text-right px-6 py-3">Credit Pool</th>
                    <th class="text-right px-6 py-3">Available</th>
                    <th class="text-center px-6 py-3">Members</th>
                    <th class="text-center px-6 py-3">Tier</th>
                    <th class="text-center px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($organizations as $org)
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-blue-800
                                        flex items-center justify-center flex-shrink-0 ring-1 ring-gold/20">
                                <span class="text-white text-xs font-bold font-mono">
                                    {{ strtoupper(substr($org->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-semibold text-white">{{ $org->name }}</p>
                                <p class="text-xs text-slate-500 font-mono">{{ $org->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($org->status === 'active')
                            <span class="badge-green">Active</span>
                        @elseif($org->status === 'suspended')
                            <span class="badge-red">Suspended</span>
                        @else
                            <span class="badge-yellow">{{ ucfirst($org->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right font-mono text-slate-300">
                        KES {{ number_format($org->credit_pool) }}
                    </td>
                    <td class="px-6 py-4 text-right font-mono text-emerald-400 font-semibold">
                        KES {{ number_format($org->available_credit_pool) }}
                    </td>
                    <td class="px-6 py-4 text-center font-mono text-slate-300">
                        {{ $org->users_count }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="badge-blue capitalize">{{ $org->subscription_tier }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('super.orgs.show', $org) }}"
                               class="text-xs px-3 py-1.5 bg-slate-800 hover:bg-slate-700
                                      text-slate-300 rounded-lg border border-slate-700
                                      font-mono transition-colors">
                                View
                            </a>
                            <a href="{{ route('super.orgs.edit', $org) }}"
                               class="text-xs px-3 py-1.5 bg-blue-900/30 hover:bg-blue-900/50
                                      text-blue-400 rounded-lg border border-blue-700/40
                                      font-mono transition-colors">
                                Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-slate-600 font-mono">
                        No organizations yet.
                        <a href="{{ route('super.orgs.create') }}"
                           class="text-gold hover:text-amber-400 ml-1 transition-colors">
                            Create the first one →
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-800/40">
        {{ $organizations->links() }}
    </div>
</div>

@endsection