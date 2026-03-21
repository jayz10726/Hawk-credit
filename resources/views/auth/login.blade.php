@extends('layouts.auth')
@section('title','Sign In')
@section('content')
<div x-data="{ loading: false, showPass: false }">
    <div class="mb-8">
        <h2 class="text-2xl font-bold font-serif text-white">Welcome back</h2>
        <p class="text-slate-500 text-sm mt-1">Sign in to your account to continue</p>
    </div>
    @if($errors->any())
    <div class="mb-5 flex items-start gap-3 p-4 bg-red-900/20 border border-red-700/40 rounded-xl text-red-400 text-sm">
        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
        </svg>
        {{ $errors->first() }}
    </div>
    @endif
    <form method="POST" action="{{ route('login') }}" @submit="loading = true" class="space-y-5">
        @csrf
        <div>
            <label class="label">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
                <input type='email' name='email' value='{{ old("email") }}' required autofocus
                       class="input pl-11" placeholder="admin@hawkscredits.com">
            </div>
        </div>
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="label mb-0">Password</label>
                @if(Route::has('password.request'))
                <a href='{{ route("password.request") }}' class='text-xs text-gold hover:text-gold-light transition-colors'>Forgot password?</a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
                </div>
                <input :type='showPass ? "text" : "password"' name='password' required
                       class="input pl-11 pr-12" placeholder="••••••••">
                <button type="button" @click="showPass = !showPass"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-300 transition-colors">
                    <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <input type="checkbox" name="remember" id="remember"
                   class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-600 focus:ring-blue-500 focus:ring-offset-slate-900">
            <label for="remember" class="text-sm text-slate-400">Keep me signed in</label>
        </div>
        <button type="submit" :disabled="loading"
                class="w-full btn-gold flex items-center justify-center gap-2 py-3 text-base">
            <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <span x-text="loading ? 'Signing in...' : 'Sign In to Hawks Credits'"></span>
        </button>
    </form>
    @if(Route::has('register'))
    <p class="text-center text-sm text-slate-500 mt-6">
        Don't have an account?
        <a href='{{ route("register") }}' class='text-gold hover:text-gold-light font-semibold ml-1 transition-colors'>Register</a>
    </p>
    @endif
</div>
@endsection
