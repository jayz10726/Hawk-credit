@extends('layouts.app')
@section('title','Organizations')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-xl font-bold text-gray-800">Organizations</h1>
    <a href="{{ route('super.orgs.create') }}"
       class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">
        + New Organization
    </a>
</div>
<div class="bg-white rounded-xl border border-gray-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="text-left px-5 py-3">Organization</th>
                    <th class="text-center px-5 py-3">Status</th>
                    <th class="text-right px-5 py-3">Credit Pool</th>
                    <th class="text-center px-5 py-3">Members</th>
                    <th class="text-center px-5 py-3">Tier</th>
                    <th class="text-center px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($organizations as $org)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <p class="font-semibold text-gray-800">{{ $org->name }}</p>
                        <p class="text-xs text-gray-400">{{ $org->email }}</p>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full font-medium
                            @if($org->status==='active') bg-green-100 text-green-700
                            @elseif($org->status==='suspended') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ ucfirst($org->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right font-medium">KES {{ number_format($org->credit_pool) }}</td>
                    <td class="px-5 py-3 text-center">{{ $org->users_count }}</td>
                    <td class="px-5 py-3 text-center capitalize">{{ $org->subscription_tier }}</td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('super.orgs.show', $org) }}"
                           class="text-xs px-3 py-1 bg-blue-50 text-blue-700 rounded-full hover:bg-blue-100 mr-1">
                            View
                        </a>
                        <a href="{{ route('super.orgs.edit', $org) }}"
                        class="text-xs px-3 py-1 bg-gray-50 text-gray-700 rounded-full hover:bg-gray-100">
                            Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">
                    No organizations yet. <a href="{{ route('super.orgs.create') }}" class="text-blue-600 hover:underline">Create one</a>.
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4">{{ $organizations->links() }}</div>
</div>
@endsection
