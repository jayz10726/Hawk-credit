@extends('layouts.app')
@section('title','Add Member')
@section('content')

<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold font-serif text-white">Add New Member</h1>
            <p class="text-slate-500 text-sm mt-1 font-mono">
                Create a member account for your organization
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-ghost">← Back</a>
    </div>

    <div class="card p-8">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            @if(session('success'))
            <div class="p-4 bg-emerald-900/20 border border-emerald-700/40 rounded-xl
                        text-emerald-400 text-sm font-mono">
                ✅ {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">First Name *</label>
                    <input type="text" name="first_name"
                           value="{{ old('first_name') }}" required
                           class="input" placeholder="John">
                    @error('first_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="label">Last Name *</label>
                    <input type="text" name="last_name"
                           value="{{ old('last_name') }}" required
                           class="input" placeholder="Doe">
                    @error('last_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="label">Email Address *</label>
                <input type="email" name="email"
                       value="{{ old('email') }}" required
                       class="input" placeholder="john@company.com">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="label">Login Password *</label>
                <input type="text" name="password" required
                       class="input" placeholder="Min. 8 characters"
                       value="{{ old('password') }}">
                <p class="text-xs text-slate-500 font-mono mt-1">
                    ⚠ Share this password with the member so they can log in.
                </p>
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Phone</label>
                    <input type="text" name="phone"
                           value="{{ old('phone') }}"
                           class="input" placeholder="+254700000000">
                </div>
                <div>
                    <label class="label">National ID</label>
                    <input type="text" name="national_id"
                           value="{{ old('national_id') }}"
                           class="input" placeholder="12345678">
                    @error('national_id')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="label">Monthly Income (KES)</label>
                <input type="number" name="monthly_income"
                       value="{{ old('monthly_income') }}"
                       class="input" placeholder="e.g. 50000" min="0">
            </div>

            {{-- Info box --}}
            <div class="p-4 bg-blue-900/20 border border-blue-700/30 rounded-xl text-sm">
                <p class="font-semibold text-blue-400 font-mono mb-2">
                    ℹ What happens after you add this member:
                </p>
                <ul class="space-y-1 text-slate-400 font-mono text-xs">
                    <li>→ They get a credit score of <span class="text-amber-400">540 (Poor)</span>
                        — eligible to apply for credit</li>
                    <li>→ Their credit limit starts at <span class="text-amber-400">KES 0</span>
                        — you must set it from their profile</li>
                    <li>→ They can log in immediately at
                        <span class="text-blue-400">{{ url('/login') }}</span></li>
                </ul>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 btn-gold py-3 text-base">
                    Add Member
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="flex-1 btn-ghost py-3 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection