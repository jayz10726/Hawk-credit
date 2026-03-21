@extends('layouts.app')
@section('title', $loan->reference_code)
@section('content')
<div x-data="{ payOpen: false }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <span class='badge-green'>Active</span>
                <span class="text-xs font-mono text-slate-500">{{ $loan->start_date->format('M j, Y') }} → {{ $loan->end_date->format('M j, Y') }}</span>
            </div>
            <h1 class="text-2xl font-bold font-serif text-white">{{ $loan->reference_code }}</h1>
        </div>
        @if($loan->status === 'active')
        <button @click="payOpen=true" class="btn-gold">Make a Payment</button>
        @endif
    </div>
    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stat-card">
            <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Principal</p>
            <p class="text-xl font-bold font-mono text-white">KES {{ number_format($loan->principal_amount) }}</p>
        </div>
        <div class="stat-card">
            <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Interest Rate</p>
            <p class="text-xl font-bold font-mono text-white">{{ $loan->interest_rate }}% p.a.</p>
        </div>
        <div class="stat-card">
            <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Outstanding</p>
            <p class="text-xl font-bold font-mono text-blue-400">KES {{ number_format($loan->outstanding_balance) }}</p>
        </div>
        <div class="stat-card">
            <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Monthly</p>
            <p class="text-xl font-bold font-mono text-gold">KES {{ number_format($loan->monthly_installment) }}</p>
        </div>
    </div>
    {{-- Progress Bar --}}
    <div class="card p-6 mb-6">
        <div class="flex justify-between items-center mb-3">
  <h2 class="font-semibold font-serif text-white">Repayment Progress</h2>
            <span class="font-mono text-lg font-bold text-emerald-400">{{ $loan->completion_percentage }}%</span>
        </div>
        <div class="w-full bg-slate-800 rounded-full h-3">
            <div class="h-3 rounded-full bg-gradient-to-r from-blue-600 to-emerald-500 transition-all duration-700"
                 style="width:{{ $loan->completion_percentage }}%"></div>
        </div>
        <div class="flex justify-between mt-2 text-xs font-mono text-slate-500">
            <span>KES {{ number_format($loan->total_paid) }} paid</span>
            <span>KES {{ number_format($loan->total_payable) }} total</span>
        </div>
    </div>
    {{-- Installment Schedule --}}
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-800/60">
            <h2 class="font-semibold font-serif text-white">Repayment Schedule</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="table-head"><tr>
                    <th class="text-center px-5 py-3">#</th>
                    <th class="text-left px-5 py-3">Due Date</th>
                    <th class="text-right px-5 py-3">Amount</th>
                    <th class="text-right px-5 py-3">Principal</th>
                    <th class="text-right px-5 py-3">Interest</th>
                    <th class="text-center px-5 py-3">Status</th>
                </tr></thead>
                <tbody>
                    @foreach($loan->installments as $inst)
                    <tr class='table-row @if($inst->status==="overdue") bg-red-900/10 @endif'>
                        <td class="px-5 py-3 text-center font-mono text-slate-500 text-xs">{{ $inst->installment_number }}</td>
                        <td class='px-5 py-3 font-mono text-sm @if($inst->due_date->isPast() && $inst->status!=="paid") text-red-400 @else text-slate-300 @endif'>
                            {{ $inst->due_date->format('M j, Y') }}
                        </td>
                        <td class="px-5 py-3 text-right font-mono font-semibold text-white">KES {{ number_format($inst->amount_due) }}</td>
                        <td class="px-5 py-3 text-right font-mono text-slate-500 text-xs">{{ number_format($inst->principal_component) }}</td>
                        <td class="px-5 py-3 text-right font-mono text-slate-500 text-xs">{{ number_format($inst->interest_component) }}</td>
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
    {{-- PAYMENT MODAL --}}
    <dialog x-ref="payDialog" x-effect="payOpen ? $refs.payDialog.showModal() : $refs.payDialog.close()"
            class="bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl p-0 w-full max-w-md backdrop:bg-black/60">
        <form method='POST' action="{{ route('user.loans.pay', $loan) }}" class='p-7'>
            @csrf
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold font-serif text-white">Make a Payment</h3>
                <button type="button" @click="payOpen=false" class="text-slate-500 hover:text-slate-300 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="label">Amount (KES)</label>
                    <input type='number' name='amount' required min='1'
                           value='{{ $loan->installments->whereNotIn("status",["paid"])->first()?->amount_due }}'
                           class='input'>
                </div>
                <div>
                    <label class="label">Payment Method</label>
                    <select name='payment_method' required class='input'>
                        <option value="mobile_money">M-Pesa / Mobile Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                    </select>
                </div>
                <div>
                    <label class="label">Reference / Receipt No.</label>
                    <input type='text' name='payment_reference' placeholder='e.g. QGH7X3K2L9' class='input'>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" @click="payOpen=false" class="flex-1 btn-ghost">Cancel</button>
                <button type="submit" class="flex-1 btn-gold">Confirm Payment ✓</button>
            </div>
        </form>
    </dialog>
</div>
@endsection
