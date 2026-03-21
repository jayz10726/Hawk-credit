@extends('layouts.app')
@section('title','Credit Requests')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold text-gray-800">Credit Requests</h1>
    <div class="flex gap-2">
        @foreach(['','submitted','under_review','approved','rejected','disbursed'] as $s)
        <a href="{{ request()->fullUrlWithQuery(['status'=>$s]) }}"
           class="px-3 py-1.5 text-xs rounded-full border @if(request('status')==$s) bg-blue-600 text-white border-blue-600 @else text-gray-600 border-gray-200 hover:bg-gray-50 @endif">
            {{ $s ? ucwords(str_replace('_',' ',$s)) : 'All' }}
        </a>
        @endforeach
    </div>
</div>
<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
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
            <tbody class="divide-y divide-gray-50">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $req->reference_code }}</td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-800">{{ $req->user->full_name }}</p>
                        <p class="text-xs text-gray-400">{{ $req->user->email }}</p>
                    </td>
                    <td class="px-5 py-3 text-right font-semibold">KES {{ number_format($req->amount_requested) }}</td>
                    <td class="px-5 py-3 text-center font-bold @if($req->score_at_application >= 660) text-green-600 @elseif($req->score_at_application >= 540) text-yellow-600 @else text-red-600 @endif">
                        {{ $req->score_at_application ?? "—" }}
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            @switch($req->status)
                                @case('submitted') bg-blue-100 text-blue-700 @break
                                @case('approved') bg-green-100 text-green-700 @break
                                @case('disbursed') bg-purple-100 text-purple-700 @break
      @case('rejected') bg-red-100 text-red-700 @break
                                @default bg-gray-100 text-gray-600
                            @endswitch">
                            {{ ucwords(str_replace('_',' ',$req->status)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center text-gray-500">{{ $req->created_at->format('M j, Y') }}</td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('admin.requests.show', $req) }}"
                           class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Review</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4">{{ $requests->links() }}</div>
</div>
@endsection
