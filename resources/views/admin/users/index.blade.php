@extends('layouts.app')
@section('title','Members')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold text-gray-100">Members</h1>
    <a href="{{ route('admin.users.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-500 transition-colors">+ Add Member</a>
</div>
<div class="bg-gray-900 rounded-xl border border-gray-700 shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-800 text-xs text-gray-400 uppercase">
            <tr>
                <th class="text-left px-5 py-3">Member</th>
                <th class="text-center px-5 py-3">Credit Score</th>
                <th class="text-right px-5 py-3">Credit Limit</th>
                <th class="text-center px-5 py-3">Status</th>
                <th class="text-center px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse($users as $user)
            <tr class="hover:bg-gray-800 transition-colors">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-100">{{ $user->full_name }}</p>
                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                </td>
                <td class="px-5 py-3 text-center font-bold
                    @if($user->creditScore?->score >= 660) text-green-400
                    @else text-yellow-400
                    @endif">
                    {{ $user->creditScore?->score ?? 300 }}
                </td>
                <td class="px-5 py-3 text-right text-gray-100">KES {{ number_format($user->creditScore?->credit_limit ?? 0) }}</td>
                <td class="px-5 py-3 text-center">
                    <span class="text-xs px-2 py-1 rounded-full
                        @if($user->is_active) bg-green-900 text-green-300
                        @else bg-red-900 text-red-300
                        @endif">
                        {{ $user->is_active ? 'Active' : 'Suspended' }}
                    </span>
                </td>
                <td class="px-5 py-3 text-center">
                    <a href="{{ route('admin.users.show', $user) }}"
                       class="text-xs px-3 py-1 bg-blue-900 text-blue-300 rounded-full hover:bg-blue-800 transition-colors">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-10 text-center text-gray-500">No members yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4">{{ $users->links() }}</div>
</div>
@endsection