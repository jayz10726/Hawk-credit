@extends('layouts.app')
@section('title','All Users')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold font-serif text-white">All Users</h1>
        <p class="text-slate-500 text-sm mt-1 font-mono">Every user across all organizations</p>
    </div>
    <div class="flex gap-3">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search name or email..."
                   class="input w-56">
            <button class="btn-primary">Search</button>
        </form>
        <a href="{{ route('super.users.create') }}" class="btn-gold">+ Create User</a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="table-head">
                <tr>
                    <th class="text-left px-5 py-3">User</th>
                    <th class="text-left px-5 py-3">Organization</th>
                    <th class="text-center px-5 py-3">Role</th>
                    <th class="text-center px-5 py-3">Status</th>
                    <th class="text-center px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="table-row">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-800
                                        flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-bold font-mono">
                                    {{ strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $user->full_name }}</p>
                                <p class="text-xs text-slate-500 font-mono">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-slate-400 font-mono text-xs">
                        {{ $user->organization?->name ?? '— Super Admin —' }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="badge-blue">
                            {{ $user->roles->first()?->name ?? 'No role' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($user->is_active)
                            <span class="badge-green">Active</span>
                        @else
                            <span class="badge-red">Suspended</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('super.users.show', $user) }}"
                           class="text-xs px-3 py-1.5 bg-slate-800 hover:bg-slate-700
                                  text-slate-300 rounded-lg border border-slate-700
                                  font-mono transition-colors">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-16 text-center text-slate-600 font-mono">
                        No users found.
                        <a href="{{ route('super.users.create') }}"
                           class="text-gold hover:text-amber-400 ml-1">Create one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-800/40">
        {{ $users->links() }}
    </div>
</div>

@endsection