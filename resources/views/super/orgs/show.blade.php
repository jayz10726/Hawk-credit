@extends('layouts.app')
@section('title', $org->name)
@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">{{ $org->name }}</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $org->email }} · {{ ucfirst($org->subscription_tier) }}</p>
    </div>
    <a href="{{ route('super.orgs.edit', $org) }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">
        Edit Organization
    </a>
</div>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['Members',        $stats['total_users'],     'text-blue-700'],
        ['Active Loans',   $stats['active_loans'],    'text-green-700'],
        ['Total Disbursed','KES '.number_format($stats['total_disbursed']), 'text-purple-700'],
        ['Pending Requests',$stats['pending_requests'],'text-yellow-700'],
] as [$label,$value,$col])
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $label }}</p>
        <p class="text-2xl font-bold {{ $col }} mt-2">{{ $value }}</p>
    </div>
    @endforeach
</div>
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
    <h2 class="font-semibold text-gray-700 mb-4">Organization Details</h2>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div><span class="text-gray-500">Status:</span> <span class="font-medium capitalize">{{ $org->status }}</span></div>
        <div><span class="text-gray-500">Credit Pool:</span> <span class="font-medium">KES {{ number_format($org->credit_pool) }}</span></div>
        <div><span class="text-gray-500">Available:</span> <span class="font-medium text-green-700">KES {{ number_format($org->available_credit_pool) }}</span></div>
        <div><span class="text-gray-500">Phone:</span> <span class="font-medium">{{ $org->phone ?? "—" }}</span></div>
        <div class="col-span-2"><span class="text-gray-500">Address:</span> <span class="font-medium">{{ $org->address ?? "—" }}</span></div>
    </div>
</div>
@endsection
