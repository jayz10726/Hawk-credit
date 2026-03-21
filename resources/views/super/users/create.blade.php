@extends('layouts.app')
@section('title','Create User')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold font-serif text-white">Create New User</h1>
        <a href="{{ route('super.users.index') }}" class="btn-ghost">← Back</a>
    </div>
    <div class="card p-8">
        <form method="POST" action="{{ route('super.users.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                           required class="input" placeholder="John">
                    @error('first_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                           required class="input" placeholder="Doe">
                    @error('last_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       required class="input" placeholder="john@company.com">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Password</label>
                <input type="password" name="password"
                       required class="input" placeholder="Min. 8 characters">
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Role</label>
                <select name="role" required class="input">
                    <option value="">Select a role</option>
                    <option value="org_admin"   {{ old('role')=='org_admin'   ? 'selected' : '' }}>Org Admin</option>
                    <option value="user"        {{ old('role')=='user'        ? 'selected' : '' }}>Normal User</option>
                    <option value="super_admin" {{ old('role')=='super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Organization</label>
                <select name="organization_id" class="input">
                    <option value="">-- None (Super Admin) --</option>
                    @foreach($organizations as $org)
                    <option value="{{ $org->id }}" {{ old('organization_id')==$org->id ? 'selected' : '' }}>
                        {{ $org->name }}
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-500 font-mono mt-1">
                    Select the organization this user belongs to
                </p>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full btn-gold py-3 text-base">
                    Create User & Assign Role
                </button>
            </div>
        </form>
    </div>

    <div class="mt-6 card p-5">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Quick Guide</p>
        <div class="space-y-2 text-sm text-slate-400">
            <p>→ <span class="text-gold font-mono">org_admin</span> — manages one organization, approves loans</p>
            <p>→ <span class="text-blue-400 font-mono">user</span> — applies for credit within their organization</p>
            <p>→ <span class="text-purple-400 font-mono">super_admin</span> — full system access, no organization needed</p>
        </div>
    </div>
</div>
@endsection