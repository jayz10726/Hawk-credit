@extends('layouts.auth')
@section('title','Create Account')
@section('content')
<div x-data="{ loading: false, showPass: false }">
    <div class="mb-8">
        <h2 class="text-2xl font-bold font-serif text-white">Create Account</h2>
        <p class="text-slate-500 text-sm mt-1">Join your organization on Hawks Credits</p>
    </div>
    <form method="POST" action="{{ route('register') }}" @submit="loading=true" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
    <label class="label">First Name</label>
                <input type='text' name='first_name' value='{{ old("first_name") }}' required
                       class='input' placeholder='John'>
                @error('first_name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="label">Last Name</label>
                <input type='text' name='last_name' value='{{ old("last_name") }}' required
                       class='input' placeholder='Doe'>
                @error('last_name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="label">Email Address</label>
            <input type='email' name='email' value='{{ old("email") }}' required
                   class='input' placeholder='john@company.com'>
            @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label">Password</label>
            <div class="relative">
                <input :type='showPass ? "text" : "password"' name='password' required
                       class='input pr-12' placeholder='Min. 8 characters'>
                <button type="button" @click="showPass=!showPass"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="label">Confirm Password</label>
            <input type='password' name='password_confirmation' required class='input' placeholder='Repeat password'>
        </div>
        <button type="submit" :disabled="loading" class="w-full btn-primary py-3 text-base">
            <span x-text="loading ? 'Creating Account...' : 'Create Account'"></span>
        </button>
    </form>
    <p class='text-center text-sm text-slate-500 mt-6'>Already have an account? <a href='{{ route("login") }}' class='text-gold hover:text-gold-light font-semibold ml-1'>Sign In</a></p>
</div>
@endsection
