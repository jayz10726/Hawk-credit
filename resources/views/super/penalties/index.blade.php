@extends('layouts.app')
@section('title','Penalty Manager')
@section('content')
<div class="flex justify-between items-center mb-8">
    <div><h1 class="text-2xl font-bold font-serif text-white">Penalty Manager</h1>
    <p class="text-slate-500 text-sm mt-1 font-mono">Total outstanding penalties: <span class="text-red-400 font-bold">KES {{ number_format($totalPenalties) }}</span></p></div>
</div>
@if($loans->count() === 0)
<div class="card p-16 text-center">
    <p class="text-4xl mb-4">✅</p>
    <h3 class="text-lg font-semibold font-serif text-white mb-2">No Outstanding Penalties</h3>
    <p class="text-slate-500 font-mono text-sm">All loans are in good standing.</p>
</div>
@else
<div class="card overflow-hidden">
 <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead class="table-head"><tr>
            <th class="text-left px-5 py-3">Loan</th><th class="text-left px-5 py-3">Member</th>
            <th class="text-left px-5 py-3">Organization</th>
            <th class="text-right px-5 py-3">Penalty Balance</th><th class="text-center px-5 py-3">Action</th>
        </tr></thead>
        <tbody>
            @foreach($loans as $loan)
            <tr class="table-row" x-data="{ waiveOpen: false }">
                <td class="px-5 py-3 font-mono text-xs text-slate-400">{{ $loan->reference_code }}</td>
                <td class="px-5 py-3"><p class="text-white font-medium">{{ $loan->user->full_name }}</p></td>
                <td class="px-5 py-3 text-xs text-slate-400 font-mono">{{ $loan->organization->name }}</td>
                <td class="px-5 py-3 text-right font-mono font-bold text-red-400">KES {{ number_format($loan->penalty_balance) }}</td>
                <td class="px-5 py-3 text-center">
                    <button @click="waiveOpen=true" class="text-xs px-3 py-1.5 bg-red-900/30 hover:bg-red-900/50 text-red-400 rounded-lg border border-red-700/40 font-mono">Waive</button>
                    <dialog x-ref="wd" x-effect="waiveOpen?$refs.wd.showModal():$refs.wd.close()" class="bg-slate-900 border border-slate-700 rounded-2xl p-0 w-full max-w-sm backdrop:bg-black/60">
                        <form method='POST' action="{{ route('super.penalties.waive',$loan) }}" class='p-6'>
                            @csrf
                            <h3 class="text-lg font-bold font-serif text-white mb-4">Waive Penalty</h3>
                            <p class="text-sm text-slate-400 mb-4 font-mono">Waiving KES {{ number_format($loan->penalty_balance) }} for {{ $loan->reference_code }}</p>
                            <label class="label">Reason</label>
                            <textarea name='reason' rows='3' required class='input resize-none mb-4' placeholder='Reason for waiving...'></textarea>
                            <div class="flex gap-3">
                                <button type="button" @click="waiveOpen=false" class="flex-1 btn-ghost">Cancel</button>
                                <button type="submit" class="flex-1 btn-primary">Confirm Waive</button>
                            </div>
                        </form>
                    </dialog>
                </td>
            </tr>
            @endforeach
        </tbody></table></div>
    <div class="px-5 py-4 border-t border-slate-800/40">{{ $loans->links() }}</div>
</div>
@endif
@endsection
