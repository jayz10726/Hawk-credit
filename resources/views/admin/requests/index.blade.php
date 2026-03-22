@extends('layouts.app')
@section('title','Credit Requests')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold text-gray-100">Credit Requests</h1>
    <div class="flex gap-2">
        @foreach(['','submitted','under_review','approved','rejected','disbursed'] as $s)
        <a href="{{ request()->fullUrlWithQuery(['status'=>$s]) }}"
           class="px-3 py-1.5 text-xs rounded-full border transition-colors
               @if(request('status')==$s)
                   bg-blue-600 text-white border-blue-600
               @else
                   text-gray-400 border-gray-600 hover:bg-gray-700 hover:text-gray-200
               @endif">
            {{ $s ? ucwords(str_replace('_',' ',$s)) : 'All' }}
        </a>
        @endforeach
    </div>
</div>
<div class="bg-gray-900 rounded-xl border border-gray-700 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-xs text-gray-400 uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Reference</th>
                    <th class="text-left px-5 py-3">Member</th>
                    <th class="text-right px-5 py-3">Amount</th>
                    <th class="text-center px-5 py-3">Score</th>
                    <th class="text-center px-5 py-3">Status</th>
                    <th class="text-center px-5 py-3">Date</th>
                    <th class="text-center px-5 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-800 transition-colors">
                    <td class="px-5 py-3 font-mono text-xs text-gray-400">{{ $req->reference_code }}</td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-100">{{ $req->user->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $req->user->email }}</p>
                    </td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-100">KES {{ number_format($req->amount_requested) }}</td>
                    <td class="px-5 py-3 text-center font-bold
                        @if($req->score_at_application >= 660) text-green-400
                        @elseif($req->score_at_application >= 540) text-yellow-400
                        @else text-red-400
                        @endif">
                        {{ $req->score_at_application ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            @switch($req->status)
                                @case('submitted') bg-blue-900 text-blue-300 @break
                                @case('approved') bg-green-900 text-green-300 @break
                                @case('disbursed') bg-purple-900 text-purple-300 @break
                                @case('rejected') bg-red-900 text-red-300 @break
                                @default bg-gray-700 text-gray-300
                            @endswitch">
                            {{ ucwords(str_replace('_',' ',$req->status)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center text-gray-500">{{ $req->created_at->format('M j, Y') }}</td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('admin.requests.show', $req) }}"
                           class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition-colors">Review</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-500">No requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4">{{ $requests->links() }}</div>
</div>
@endsection