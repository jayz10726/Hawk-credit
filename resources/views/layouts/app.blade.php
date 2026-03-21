<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Dashboard') — Hawks Credits</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-hawk-950 font-sans antialiased" x-data="{ sidebarOpen: false }">
{{-- ═══ MOBILE OVERLAY ═══ --}}
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
     class="fixed inset-0 z-40 bg-black/70 backdrop-blur-sm lg:hidden" x-transition:enter="transition duration-300"
     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
{{-- ═══ SIDEBAR ═══ --}}
<aside class="fixed inset-y-0 left-0 z-50 w-72 flex flex-col bg-slate-950 border-r border-slate-800/60
           transform transition-transform duration-300 ease-in-out lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-6 border-b border-slate-800/60">
        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 ring-1 ring-gold/30">
            <svg class="w-5 h-5 text-gold" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
            </svg>
        </div>
        <div>
            <p class="font-bold font-serif text-white text-sm leading-tight">Hawks Credits</p>
            <p class="text-slate-500 text-xs font-mono">
                @auth {{ auth()->user()->organization?->name ?? 'Super Admin' }} @endauth
            </p>
        </div>
    </div>
    {{-- Navigation --}}
    <nav class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">
        @role('super_admin')
        <p class="px-3 mb-2 text-xs font-mono font-semibold text-slate-600 uppercase tracking-widest">System</p>
        <x-nav-item :href="route('super.dashboard')" :active="request()->routeIs('super.dashboard')">
            <x-slot:icon>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </x-slot:icon>
     Dashboard
        </x-nav-item>
        <x-nav-item :href="route('super.orgs.index')" :active="request()->routeIs('super.orgs.*')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></x-slot:icon>
            Organizations
        </x-nav-item>
        <x-nav-item :href="route('super.users.index')" :active="request()->routeIs('super.users.*')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></x-slot:icon>
            All Users
        </x-nav-item>
        <x-nav-item :href="route('super.analytics')" :active="request()->routeIs('super.analytics')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></x-slot:icon>
            Analytics
        </x-nav-item>
        @endrole
        @role('org_admin')
        <p class="px-3 mb-2 text-xs font-mono font-semibold text-slate-600 uppercase tracking-widest">Management</p>
        <x-nav-item :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></x-slot:icon>
            Dashboard
        </x-nav-item>
        <x-nav-item :href="route('admin.requests.index')" :active="request()->routeIs('admin.requests.*')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></x-slot:icon>
            Credit Requests
        </x-nav-item>
        <x-nav-item :href="route('admin.loans.index')" :active="request()->routeIs('admin.loans.*')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot:icon>
            Loan Portfolio
        </x-nav-item>
        <x-nav-item :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg></x-slot:icon>
            Members
        </x-nav-item>
        <x-nav-item :href="route('admin.analytics')" :active="request()->routeIs('admin.analytics')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 
012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></x-slot:icon>
            Analytics
        </x-nav-item>
        @endrole
        @role('user')
        <p class="px-3 mb-2 text-xs font-mono font-semibold text-slate-600 uppercase tracking-widest">My Account</p>
        <x-nav-item :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg></x-slot:icon>
            Dashboard
        </x-nav-item>
        <x-nav-item :href="route('user.requests.index')" :active="request()->routeIs('user.requests.*')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></x-slot:icon>
            My Applications
        </x-nav-item>
        <x-nav-item :href="route('user.loans.index')" :active="request()->routeIs('user.loans.*')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></x-slot:icon>
            My Loans
        </x-nav-item>
        <x-nav-item :href="route('user.score')" :active="request()->routeIs('user.score')">
            <x-slot:icon><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg></x-slot:icon>
            Credit Score
        </x-nav-item>
        @endrole
    </nav>
    {{-- User profile footer --}}
    <div class="px-4 py-4 border-t border-slate-800/60">
        <div class="flex items-center gap-3 px-2">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center ring-1 ring-gold/20 flex-shrink-0">
                <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->first_name,0,1)).strtoupper(substr(auth()->user()->last_name,0,1)) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->full_name }}</p>
                <p class="text-xs text-slate-500 truncate font-mono">{{ auth()->user()->roles->first()?->name }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors" title="Sign out">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
  </div>
</aside>
{{-- ═══ MAIN CONTENT ═══ --}}
<div class="lg:pl-72 min-h-screen flex flex-col">
    {{-- Topbar --}}
    <header class="sticky top-0 z-30 bg-slate-950/90 backdrop-blur-sm border-b border-slate-800/60">
        <div class="flex items-center justify-between px-4 sm:px-6 h-16">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen=true" class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="text-sm font-semibold text-slate-300 hidden sm:block font-mono">
                    / @yield('title','Dashboard')
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-900/30 border border-emerald-700/30 text-emerald-400 text-xs font-mono">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span> LIVE
                </span>
                <span class="text-xs text-slate-500 font-mono hidden md:block">{{ now()->format('D, M j · H:i') }}</span>
            </div>
        </div>
    </header>
    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)" x-cloak
         x-transition:leave="transition duration-300" x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 px-4 py-3 bg-emerald-900/30 border border-emerald-700/40 rounded-xl text-emerald-400 text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
        {{ session('success') }}
        <button @click="show=false" class="ml-auto text-emerald-500 hover:text-emerald-300">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
        </button>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 px-4 py-3 bg-red-900/30 border border-red-700/40 rounded-xl text-red-400 text-sm">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></svg>
        {{ session('error') }}
    </div>
    @endif
    <main class="flex-1 p-4 sm:p-6">
        @yield('content')
    </main>
    <footer class="px-6 py-4 border-t border-slate-800/40 text-center">
        <p class="text-xs text-slate-600 font-mono">Hawks Credits v1.0 · MSc Final Year Project · Built with Laravel 12 + Breeze</p>
    </footer>
</div>
@stack('scripts')
</body>
</html>
