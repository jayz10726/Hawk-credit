@extends('layouts.app')
@section('title','My Applications')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold text-gray-100">My Credit Applications</h1>
    <a href="{{ route('user.requests.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-500 transition-colors">+ Apply for Credit</a>
</div>
<div class="bg-gray-900 rounded-xl border border-gray-700 shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-800 text-xs text-gray-400 uppercase">
            <tr>
                <th class="text-left px-5 py-3">Reference</th>
                <th class="text-right px-5 py-3">Amount</th>
                <th class="text-center px-5 py-3">Tenure</th>
                <th class="text-left px-5 py-3">Purpose</th>
                <th class="text-center px-5 py-3">Status</th>
                <th class="text-center px-5 py-3">Date</th>
                <th class="text-center px-5 py-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse($requests as $req)
            <tr class="hover:bg-gray-800 transition-colors">
                <td class="px-5 py-3 font-mono text-xs text-gray-400">{{ $req->reference_code }}</td>
                <td class="px-5 py-3 text-right font-semibold text-gray-100">KES {{ number_format($req->amount_requested) }}</td>
                <td class="px-5 py-3 text-center text-gray-300">{{ $req->tenure_months }}mo</td>
                <td class="px-5 py-3 text-gray-400">{{ $req->purpose }}</td>
                <td class="px-5 py-3 text-center">
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        @switch($req->status)
                            @case('submitted') bg-blue-900 text-blue-300 @break
                            @case('approved')
                            @case('disbursed') bg-green-900 text-green-300 @break
                            @case('rejected') bg-red-900 text-red-300 @break
                            @default bg-gray-700 text-gray-300
                        @endswitch">
                        {{ ucwords(str_replace('_',' ',$req->status)) }}
                    </span>
                </td>
                <td class="px-5 py-3 text-center text-gray-500">{{ $req->created_at->format('M j') }}</td>
                <td class="px-5 py-3 text-center">
                    <a href="{{ route('user.requests.show', $req) }}"
                       class="text-xs px-3 py-1 bg-blue-900 text-blue-300 rounded-full hover:bg-blue-800 transition-colors">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-10 text-center text-gray-500">
                    No applications yet.
                    <a href="{{ route('user.requests.create') }}" class="text-blue-400 hover:underline">Apply now</a>.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4">{{ $requests->links() }}</div>
</div>
@endsection