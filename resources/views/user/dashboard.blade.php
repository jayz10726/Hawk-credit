@extends('layouts.app')
@section('title','My Dashboard')
@section('content')
{{-- Hero greeting --}}
<div class="relative mb-8 p-6 rounded-2xl overflow-hidden bg-hawk-gradient border border-slate-700/40",
     style="background:linear-gradient(135deg,#0B1E3D 0%,#1E3A8A 100%)">
    <div class="absolute top-0 right-0 w-64 h-64 bg-gold/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="relative">
        <p class="text-slate-400 text-sm font-mono">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},</p>
        <h1 class="text-2xl font-bold font-serif text-white mt-1">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h1>
        <p class="text-slate-400 text-sm mt-2">Here's your financial overview for today.</p>
    </div>
    {{-- Credit Score Pill --}}
    @if($score)
    <div class="absolute top-6 right-6 text-right hidden sm:block">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-1">Credit Score</p>
        <p class='text-4xl font-bold font-mono @if($score->score >= 660) text-emerald-400 @elseif($score->score >= 540) text-amber-400 @else text-red-400 @endif'>
            {{ $score->score }}
        </p>
        <p class="text-xs font-mono text-slate-500">/ 850 · {{ ucfirst($score->band) }}</p>
    </div>
    @endif
</div>
{{-- Stats Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Available Credit</p>
        <p class='text-2xl font-bold font-mono @if($score?->available_credit > 0) text-emerald-400 @else text-slate-400 @endif'>
            KES {{ number_format($score?->available_credit ?? 0) }}
        </p>
        <div class="mt-3 w-full bg-slate-800 rounded-full h-1.5">
            @php $util = $score && $score->credit_limit > 0 ? (($score->credit_limit - $score->available_credit)/$score->credit_limit)*100 : 0 @endphp
            <div class="h-1.5 rounded-full transition-all duration-500 @if($util < 30) bg-emerald-400 @elseif($util < 70) bg-amber-400 @else bg-red-400 @endif"
                 style="width:{{ min(100,$util) }}%"></div>
        </div>
        <p class="text-xs font-mono text-slate-600 mt-1">{{ round($util) }}% utilized</p>
    </div>
 <div class="stat-card">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Active Loans</p>
        <p class="text-2xl font-bold font-mono text-white">{{ $activeLoans->count() }}</p>
        <p class="text-xs font-mono text-slate-500 mt-1">KES {{ number_format($activeLoans->sum('outstanding_balance')) }} outstanding</p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Next Payment</p>
        @if($nextDue)
        <p class='text-2xl font-bold font-mono @if($nextDue->due_date->isPast()) text-red-400 @else text-white @endif'>
            {{ $nextDue->due_date->format('M j') }}
        </p>
        <p class="text-xs font-mono text-slate-500 mt-1">KES {{ number_format($nextDue->amount_due) }}</p>
        @else
        <p class="text-2xl font-bold font-mono text-slate-600">—</p>
        <p class="text-xs font-mono text-slate-600 mt-1">No payments due</p>
        @endif
    </div>
    <div class="stat-card flex flex-col justify-between">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest">Quick Action</p>
        <a href="{{ route('user.requests.create') }}" class='btn-gold text-center mt-4'>Apply for Credit</a>
    </div>
</div>
{{-- Active Loans Table --}}
@if($activeLoans->count() > 0)
<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-800/60 flex justify-between items-center">
        <h2 class="font-semibold font-serif text-white">Active Loans</h2>
        <a href="{{ route('user.loans.index') }}" class='text-xs text-blue-400 hover:text-blue-300 font-mono'>View all →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="table-head"><tr>
                <th class="text-left px-6 py-3">Reference</th>
                <th class="text-right px-6 py-3">Principal</th>
                <th class="text-right px-6 py-3">Outstanding</th>
                <th class="text-center px-6 py-3">Progress</th>
                <th class="text-center px-6 py-3">Next Due</th>
                <th class="text-center px-6 py-3">Action</th>
            </tr></thead>
            <tbody>
                @foreach($activeLoans as $loan)
                <tr class="table-row">
                    <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $loan->reference_code }}</td>
                    <td class="px-6 py-4 text-right font-mono text-slate-300">KES {{ number_format($loan->principal_amount) }}</td>
                    <td class="px-6 py-4 text-right font-mono text-blue-400 font-semibold">KES {{ number_format($loan->outstanding_balance) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-full bg-slate-800 rounded-full h-1.5 max-w-[80px]">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width:{{ $loan->completion_percentage }}%"></div>
                            </div>
                            <span class="text-xs font-mono text-slate-500">{{ $loan->completion_percentage }}%</span>
                        </div>
                    </td>
                    <td class='px-6 py-4 text-center font-mono text-xs @if($loan->next_due_date?->isPast()) text-red-400 @else text-slate-400 @endif'>
                        {{ $loan->next_due_date?->format('M j, Y') ?? '—' }}
                    </td>
<td class="px-6 py-4 text-center">
                        <a href="{{ route('user.loans.show', $loan) }}" class='text-xs px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700 font-mono transition-colors'>View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card p-12 text-center">
    <div class="w-16 h-16 rounded-2xl bg-blue-900/20 border border-blue-700/20 flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2M12 8V7m0 9v1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <h3 class="text-lg font-semibold font-serif text-white mb-2">No Active Loans</h3>
    <p class="text-slate-500 text-sm mb-6">Apply for credit to get started on your financial journey.</p>
    <a href="{{ route('user.requests.create') }}" class='btn-gold inline-flex'>Apply for Credit →</a>
</div>
@endif
@endsection
