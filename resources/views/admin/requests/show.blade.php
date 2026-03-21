@extends('layouts.app')
@section('title','Review Request')
@section('content')
<div class="max-w-4xl mx-auto" x-data="{ approveOpen: false, rejectOpen: false }">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-800">{{ $req->reference_code }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">Submitted {{ $req->created_at->diffForHumans() }}</p>
        </div>
        @if($req->isApprovable())
        <div class="flex gap-3">
            <button @click="rejectOpen = true"
                    class="px-4 py-2 border border-red-200 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50">Reject</button>
            <button @click="approveOpen = true"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg">Approve & Disburse</button>
        </div>
        @endif
    </div>
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h2 class="font-semibold text-gray-700 mb-3">Applicant</h2>
                <p class="font-semibold text-gray-800">{{ $req->user->full_name }}</p>
                <p class="text-sm text-gray-500">{{ $req->user->email }}</p>
                <div class="mt-3 pt-3 border-t border-gray-100 text-sm space-y-1">
                    <div class="flex justify-between"><span class="text-gray-500">Score</span>
                        <span class="font-bold @if($req->score_at_application >= 660) text-green-600 @else text-red-600 @endif">{{ $req->score_at_application }}</span>
                    </div>
                    <div class="flex justify-between"><span class="text-gray-500">Income</span>
 <span class="font-medium">KES {{ number_format($req->user->monthly_income ?? 0) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-semibold text-gray-700 mb-4">Request Details</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500">Amount</p><p class="font-bold text-gray-800 text-lg">KES {{ number_format($req->amount_requested) }}</p></div>
                    <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500">Tenure</p><p class="font-bold text-gray-800 text-lg">{{ $req->tenure_months }} months</p></div>
                    <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500">Purpose</p><p class="font-medium text-gray-800">{{ $req->purpose }}</p></div>
                    <div class="bg-gray-50 rounded-lg p-3"><p class="text-xs text-gray-500">Status</p><p class="font-medium capitalize">{{ str_replace('_',' ',$req->status) }}</p></div>
                </div>
                @if($req->purpose_details)
                <div class="mt-4 p-4 bg-gray-50 rounded-lg text-sm text-gray-700">
                    <p class="text-xs text-gray-500 font-medium mb-1 uppercase tracking-wide">Details</p>
                    {{ $req->purpose_details }}
                </div>
                @endif
            </div>
        </div>
    </div>
    {{-- APPROVE MODAL --}}
    <dialog x-ref="approveD" x-effect="approveOpen ? $refs.approveD.showModal() : $refs.approveD.close()"
            class="rounded-2xl shadow-2xl p-0 w-full max-w-md backdrop:bg-black/50">
        <form method="POST" action="{{ route('admin.requests.approve', $req) }}" class="p-6">
            @csrf
            <h3 class="text-lg font-bold text-gray-800 mb-4">Approve & Disburse</h3>
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Amount to Approve (KES)</label>
                    <input type="number" name="amount_approved" value="{{ $req->amount_requested }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Annual Interest Rate (%)</label>
                    <input type="number" name="interest_rate" step="0.5" placeholder="e.g. 12.5" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                    <textarea name="notes" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 outline-none resize-none"></textarea></div>
            </div>
            <div class="flex gap-3 mt-5">
                <button type="button" @click="approveOpen=false"
                        class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg">Confirm Approval</button>
            </div>
        </form>
    </dialog>
 <dialog x-ref="rejectD" x-effect="rejectOpen ? $refs.rejectD.showModal() : $refs.rejectD.close()"
            class="rounded-2xl shadow-2xl p-0 w-full max-w-md backdrop:bg-black/50">
        <form method="POST" action="{{ route('admin.requests.reject', $req) }}" class="p-6">
            @csrf
            <h3 class="text-lg font-bold text-gray-800 mb-4">Reject Request</h3>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection</label>
                <textarea name="reason" rows="4" required placeholder="Explain why..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-400 outline-none resize-none"></textarea></div>
            <div class="flex gap-3 mt-5">
                <button type="button" @click="rejectOpen=false"
                        class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg">Confirm Rejection</button>
            </div>
        </form>
    </dialog>
</div>
@endsection
