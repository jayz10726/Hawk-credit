@extends('layouts.app')
@section('title','My Loans')
@section('content')
<h1 class="text-xl font-bold text-gray-300 mb-6">My Loans</h1>
<div class="bg-gray-800 rounded-xl border border-gray-600    shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-800 text-xs text-gray-300 uppercase">
  <tr>
                <th class="text-left px-5 py-3">Reference</th>
                <th class="text-right px-5 py-3">Principal</th>
                <th class="text-right px-5 py-3">Outstanding</th>
                <th class="text-center px-5 py-3">Progress</th>
                <th class="text-center px-5 py-3">Status</th>
                <th class="text-center px-5 py-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($loans as $loan)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $loan->reference_code }}</td>
                <td class="px-5 py-3 text-right font-medium">KES {{ number_format($loan->principal_amount) }}</td>
                <td class="px-5 py-3 text-right text-blue-700 font-semibold">KES {{ number_format($loan->outstanding_balance) }}</td>
                <td class="px-5 py-3">
                    <div class="w-full bg-gray-100 rounded-full h-2 max-w-[100px] mx-auto">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $loan->completion_percentage }}%"></div>
                    </div>
                    <p class="text-xs text-center text-gray-400 mt-0.5">{{ $loan->completion_percentage }}%</p>
                </td>
                <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-1 rounded-full @if($loan->status==='active') bg-green-100 text-green-700 @elseif($loan->status==='completed') bg-blue-100 text-blue-700 @else bg-red-100 text-red-700 @endif capitalize">{{ $loan->status }}</span></td>
                <td class="px-5 py-3 text-center">
                    <a href="{{ route('user.loans.show', $loan) }}" class="text-xs px-3 py-1 bg-blue-50 text-blue-700 rounded-full hover:bg-blue-100">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">No loans yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4">{{ $loans->links() }}</div>
</div>
@endsection
