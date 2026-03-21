@extends('layouts.app')
@section('title','Loan Portfolio')
@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold font-serif text-white">Loan Portfolio</h1>
        <p class="text-slate-500 text-sm mt-1 font-mono">All loans for {{ auth()->user()->organization?->name }}</p>
    </div>
    <div class="flex gap-2">
        @foreach(['','active','completed','defaulted'] as $s)
        <a href='{{ request()->fullUrlWithQuery(["status"=>$s]) }}'
           class='px-3 py-1.5 text-xs rounded-lg border font-mono transition-colors @if(request("status")==$s) bg-gold text-hawk-950 border-gold font-bold @else text-slate-400 border-slate-700 hover:border-slate-500 @endif'>
            {{ $s ? ucfirst($s) : 'All' }}
        </a>
        @endforeach
    </div>
</div>
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="table-head"><tr>
                <th class="text-left px-5 py-3">Reference</th>
                <th class="text-left px-5 py-3">Member</th>
                <th class="text-right px-5 py-3">Principal</th>
                <th class="text-right px-5 py-3">Outstanding</th>
                <th class="text-center px-5 py-3">Progress</th>
                <th class="text-center px-5 py-3">Next Due</th>
                <th class="text-center px-5 py-3">Status</th>
                <th class="text-center px-5 py-3">Action</th>
            </tr></thead>
            <tbody>
                @forelse($loans as $loan)
                <tr class="table-row">
                    <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ $loan->reference_code }}</td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-white text-sm">{{ $loan->user->full_name }}</p>
                        <p class="text-xs text-slate-500 font-mono">{{ $loan->user->email }}</p>
                    </td>
                    <td class="px-5 py-3 text-right font-mono text-slate-300">KES {{ number_format($loan->principal_amount) }}</td>
                    <td class="px-5 py-3 text-right font-mono text-blue-400 font-semibold">KES {{ number_format($loan->outstanding_balance) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex flex-col items-center gap-1">
<div class="w-20 bg-slate-800 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width:{{ $loan->completion_percentage }}%"></div>
                            </div>
                            <span class="text-xs font-mono text-slate-500">{{ $loan->completion_percentage }}%</span>
                        </div>
                    </td>
                    <td class='px-5 py-3 text-center text-xs font-mono @if($loan->next_due_date?->isPast()) text-red-400 @else text-slate-400 @endif'>
                        {{ $loan->next_due_date?->format('M j') ?? '—' }}
                    </td>
                    <td class='px-5 py-3 text-center'><span class='@if($loan->status==="active") badge-green @elseif($loan->status==="completed") badge-blue @else badge-red @endif'>{{ ucfirst($loan->status) }}</span></td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('admin.loans.show', $loan) }}" class='text-xs px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg border border-slate-700 font-mono transition-colors'>View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-16 text-center text-slate-600 font-mono">No loans yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-800/40">{{ $loans->links() }}</div>
</div>
@endsection
