@extends('layouts.app')
@section('title', $user->full_name)
@section('content')
<div class="flex items-center justify-between mb-8">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
            <span class="text-white text-lg font-bold font-mono">{{ strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) }}</span>
        </div>
        <div>
            <h1 class="text-2xl font-bold font-serif text-white">{{ $user->full_name }}</h1>
            <p class="text-slate-500 text-sm font-mono">{{ $user->email }}</p>
            <p class="text-xs text-slate-600 font-mono mt-0.5">{{ $user->organization?->name ?? "Super Admin" }}</p>
        </div>
    </div>
    <a href="{{ route('super.users.index') }}" class='btn-ghost'>← Back</a>
</div>
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-4">Account Details</h2>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-slate-500">Role</span><span class="badge-blue">{{ $user->roles->first()?->name ?? "None" }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Status</span>
                <span class='@if($user->is_active) badge-green @else badge-red @endif'>{{ $user->is_active ? 'Active' : 'Suspended' }}</span>
            </div>
            <div class="flex justify-between"><span class="text-slate-500">Organization</span><span class="text-slate-300 font-mono text-xs">{{ $user->organization?->name ?? "—" }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Joined</span><span class="text-slate-300 font-mono text-xs">{{ $user->created_at->format("M j, Y") }}</span></div>
        </div>
    </div>
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-4">Update Role & Status</h2>
        <form method='POST' action="{{ route('super.users.update', $user) }}" class='space-y-4'>
            @csrf @method('PUT')
            <div>
                <label class="label">Assign Role</label>
                <select name='role' class='input'>
                    @foreach(['super_admin','org_admin','user'] as $r)
                    <option value='{{ $r }}' @selected($user->hasRole($r))>{{ ucwords(str_replace('_',' ',$r)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-3">
   <input type='checkbox' name='is_active' id='act' value='1' @checked($user->is_active)
                       class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-600">
                <label for='act' class='text-sm text-slate-300'>Account Active</label>
            </div>
            <button type="submit" class="w-full btn-primary">Update User</button>
        </form>
    </div>
</div>
@endsection
