@extends('layouts.app')
@section('title','Reports & Exports')
@section('content')
<div class="mb-8"><h1 class="text-2xl font-bold font-serif text-white">Reports & Exports</h1>
    <p class="text-slate-500 text-sm mt-1 font-mono">Download data as CSV for analysis</p></div>
<div class="grid lg:grid-cols-3 gap-6">
    <div class="card p-6 flex flex-col">
        <div class="w-12 h-12 rounded-xl bg-blue-900/30 border border-blue-700/30 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/></svg>
        </div>
<h3 class="font-bold font-serif text-white text-lg mb-2">Loans Export</h3>
        <p class="text-slate-500 text-sm font-mono mb-6 flex-1">All loans with member details, amounts, interest rates, outstanding balances and status.</p>
        <a href="{{ route('super.reports.export.loans') }}" class='btn-primary text-center'>
            ↓ Download Loans CSV
        </a>
    </div>
    <div class="card p-6 flex flex-col">
        <div class="w-12 h-12 rounded-xl bg-purple-900/30 border border-purple-700/30 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857"/></svg>
        </div>
        <h3 class="font-bold font-serif text-white text-lg mb-2">Users Export</h3>
        <p class="text-slate-500 text-sm font-mono mb-6 flex-1">All users with their organization, role, credit score, credit limit, and account status.</p>
        <a href="{{ route('super.reports.export.users') }}" class='btn-primary text-center'>
            ↓ Download Users CSV
        </a>
    </div>
    <div class="card p-6 flex flex-col">
        <div class="w-12 h-12 rounded-xl bg-emerald-900/30 border border-emerald-700/30 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="font-bold font-serif text-white text-lg mb-2">Repayments Export</h3>
        <p class="text-slate-500 text-sm font-mono mb-6 flex-1">All confirmed repayments with member, loan reference, amount, method and date.</p>
        <a href="{{ route('super.reports.export.repayments') }}" class='btn-primary text-center'>
            ↓ Download Repayments CSV
        </a>
    </div>
</div>
@endsection
