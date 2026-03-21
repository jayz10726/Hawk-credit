@extends('layouts.app')
@section('title', $loan->reference_code)
@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <div class="flex items-center gap-3 mb-1">
            <span class='badge-green'>{{ ucfirst($loan->status) }}</span>
            <span class="text-xs font-mono text-slate-500">{{ $loan->reference_code }}</span>
        </div>
        <h1 class="text-2xl font-bold font-serif text-white">
            {{ $loan->user->full_name }}
        </h1>
        <p class="text-slate-500 text-sm font-mono">{{ $loan->user->email }}</p>
    </div>
    <a href="{{ route('admin.loans.index') }}" class='btn-ghost'>← Back to Loans</a>
</div>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['Principal',          'KES '.number_format($loan->principal_amount), 'text-white'],
        ['Interest Rate',      $loan->interest_rate.'% p.a.',                'text-white'],
        ['Outstanding Balance','KES '.number_format($loan->outstanding_balance),'text-blue-400'],
        ['Monthly Installment','KES '.number_format($loan->monthly_installment),'text-gold'],
    ] as [$label,$value,$color])
    <div class="stat-card">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">{{ $label }}</p>
        <p class="text-xl font-bold font-mono {{ $color }}">{{ $value }}</p>
    </div>
    @endforeach
</div>
<div class="card p-6 mb-6">
    <div class="flex justify-between items-center mb-3">
        <h2 class="font-semibold font-serif text-white">Repayment Progress</h2>
        <span class="font-mono text-lg font-bold text-emerald-400">{{ $loan->completion_percentage }}%</span>
    </div>
    <div class="w-full bg-slate-800 rounded-full h-3">
   <div class="h-3 rounded-full bg-gradient-to-r from-blue-600 to-emerald-500"
             style="width:{{ $loan->completion_percentage }}%"></div>
    </div>
    <div class="flex justify-between mt-2 text-xs font-mono text-slate-500">
        <span>KES {{ number_format($loan->total_paid) }} paid</span>
        <span>KES {{ number_format($loan->total_payable) }} total</span>
    </div>
</div>
<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-800/60">
        <h2 class="font-semibold font-serif text-white">Repayment Schedule</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="table-head"><tr>
                <th class="text-center px-5 py-3">#</th>
                <th class="text-left px-5 py-3">Due Date</th>
                <th class="text-right px-5 py-3">Amount Due</th>
                <th class="text-right px-5 py-3">Paid</th>
                <th class="text-center px-5 py-3">Status</th>
            </tr></thead>
            <tbody>
                @foreach($loan->installments as $inst)
                <tr class='table-row @if($inst->status==="overdue") bg-red-900/10 @endif'>
                    <td class="px-5 py-3 text-center font-mono text-xs text-slate-500">{{ $inst->installment_number }}</td>
                    <td class='px-5 py-3 font-mono text-sm @if($inst->due_date->isPast()&&$inst->status!=="paid") text-red-400 @else text-slate-300 @endif'>{{ $inst->due_date->format("M j, Y") }}</td>
                    <td class="px-5 py-3 text-right font-mono font-semibold text-white">KES {{ number_format($inst->amount_due) }}</td>
                    <td class="px-5 py-3 text-right font-mono text-emerald-400">KES {{ number_format($inst->amount_paid) }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class='@switch($inst->status) @case("paid") badge-green @break @case("overdue") badge-red @break @case("partial") badge-yellow @break @default badge-blue @endswitch'>
                            {{ ucfirst($inst->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
