@extends('layouts.app')
@section('title','Credit Pools')
@section('content')
<div class="mb-8"><h1 class="text-2xl font-bold font-serif text-white">Credit Pool Manager</h1>
    <p class="text-slate-500 text-sm mt-1 font-mono">Allocate and adjust credit limits per organization</p></div>
<div class="space-y-4">
    @foreach($organizations as $org)
    <div class="card p-6" x-data="{ open: false }">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                    <span class="text-white text-sm font-bold font-mono">{{ strtoupper(substr($org->name,0,2)) }}</span>
                </div>
  <div>
                    <p class="font-semibold font-serif text-white">{{ $org->name }}</p>
                    <p class="text-xs text-slate-500 font-mono">{{ $org->users_count }} members · {{ ucfirst($org->subscription_tier) }}</p>
                </div>
            </div>
            <div class="flex items-center gap-6 text-right">
                <div><p class="text-xs text-slate-500 font-mono">Total Pool</p><p class="font-bold font-mono text-white">KES {{ number_format($org->credit_pool) }}</p></div>
                <div><p class="text-xs text-slate-500 font-mono">Available</p><p class="font-bold font-mono text-emerald-400">KES {{ number_format($org->available_credit_pool) }}</p></div>
                <div><p class="text-xs text-slate-500 font-mono">Disbursed</p><p class="font-bold font-mono text-blue-400">KES {{ number_format($org->total_disbursed ?? 0) }}</p></div>
                <button @click="open=!open" class="btn-gold text-xs px-4 py-2">Adjust</button>
            </div>
        </div>
        <div x-show="open" x-transition class="mt-5 pt-5 border-t border-slate-800/60">
            <form method='POST' action="{{ route('super.credit-pools.update', $org) }}" class='flex gap-4 items-end flex-wrap'>
                @csrf
                <div>
                    <label class="label">Action</label>
                    <select name='action' class='input w-36'>
                        <option value='set'>Set to amount</option>
                        <option value='add'>Add to pool</option>
                        <option value='subtract'>Subtract from pool</option>
                    </select>
                </div>
                <div>
                    <label class="label">Amount (KES)</label>
                    <input type='number' name='credit_pool' min='0' step='10000' class='input w-48' placeholder='e.g. 1000000'>
                </div>
                <button type="submit" class="btn-primary">Apply Change</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
