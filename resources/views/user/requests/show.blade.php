@extends('layouts.app')
@section('title', $req->reference_code)
@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-gray-800 mb-6">{{ $req->reference_code }}</h1>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500">Amount Requested</p><p class="font-bold text-gray-800">KES {{ number_format($req->amount_requested) }}</p></div>
            <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500">Tenure</p><p class="font-bold text-gray-800">{{ $req->tenure_months }} months</p></div>
            <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500">Purpose</p><p class="font-medium text-gray-800">{{ $req->purpose }}</p></div>
            <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500">Status</p><p class="font-bold capitalize @if($req->status==='disbursed'||'approved') text-green-700 @elseif($req->status==='rejected') text-red-700 @else text-blue-700 @endif">{{ str_replace('_',' ',$req->status) }}</p></div>
        </div>
        @if($req->status === 'disbursed' && $req->loan)
        <div class="bg-green-50 border border-green-100 rounded-lg p-4 text-sm">
            <p class="font-semibold text-green-800 mb-1">✅ Loan Disbursed!</p>
            <p class="text-green-700">Loan reference: <strong>{{ $req->loan->reference_code }}</strong></p>
            <a href="{{ route('user.loans.show', $req->loan) }}" class="mt-2 inline-block text-sm text-green-700 underline">View Loan &amp; Schedule →</a>
        </div>
        @endif
        @if($req->rejection_reason)
        <div class="bg-red-50 border border-red-100 rounded-lg p-4 text-sm">
            <p class="font-semibold text-red-800 mb-1">❌ Rejection Reason</p>
            <p class="text-red-700">{{ $req->rejection_reason }}</p>
        </div>
        @endif
        <a href="{{ route('user.requests.index') }}" class="inline-block text-sm text-blue-600 hover:underline">← Back to Applications</a>
    </div>
</div>
@endsection

