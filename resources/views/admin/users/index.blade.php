@extends('layouts.app')
@section('title','Members')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold text-gray-800">Members</h1>
    <a href="{{ route('admin.users.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">+ Add Member</a>
</div>
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="text-left px-5 py-3">Member</th>
                <th class="text-center px-5 py-3">Credit Score</th>
                <th class="text-right px-5 py-3">Credit Limit</th>
                <th class="text-center px-5 py-3">Status</th>
                <th class="text-center px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $user->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                </td>
                <td class="px-5 py-3 text-center font-bold @if($user->creditScore?->score >= 660) text-green-600 @else text-yellow-600 @endif">
                    {{ $user->creditScore?->score ?? 300 }}
                </td>
                <td class="px-5 py-3 text-right">KES {{ number_format($user->creditScore?->credit_limit ?? 0) }}</td>
                <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-1 rounded-full @if($user->is_active) bg-green-100 text-green-700 @else bg-red-100 text-red-700 @endif">{{ $user->is_active ? 'Active' : 'Suspended' }}</span></td>
                <td class="px-5 py-3 text-center">
   <a href="{{ route('admin.users.show', $user) }}" class="text-xs px-3 py-1 bg-blue-50 text-blue-700 rounded-full hover:bg-blue-100">View</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">No members yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4">{{ $users->links() }}</div>
</div>
@endsection
