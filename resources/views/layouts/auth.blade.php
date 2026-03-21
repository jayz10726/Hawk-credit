<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — Hawks Credits</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-full bg-hawk-gradient overflow-hidden relative" x-data>
    {{-- Animated background orbs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gold/10 rounded-full blur-3xl animate-pulse" style="animation-delay:2s"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-900/5 rounded-full blur-3xl"></div>
    </div>
    {{-- Grid pattern overlay --}}
    <div class="absolute inset-0 opacity-5" style="background-image:linear-gradient(rgba(255,255,255,.1) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.1) 1px,transparent 1px);background-size:60px 60px"></div>
    {{-- Content --}}
    <div class="relative z-10 min-h-full flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl
                            bg-gradient-to-br from-blue-600 to-blue-800 shadow-gold mb-5 ring-1 ring-gold/30">
                    <svg class="w-9 h-9 text-gold" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold font-serif text-white tracking-tight">Hawks Credits</h1>
                <p class="text-slate-500 text-sm mt-1 font-mono">Enterprise Credit Management</p>
            </div>
            {{-- Card --}}
            <div class="card-glass p-8 ring-1 ring-slate-700/50 shadow-gold">
                @yield('content')
            </div>
            {{-- Footer --}}
            <p class="text-center text-xs text-slate-600 mt-6 font-mono">
                © {{ date('Y') }} Hawks Credits · MSc Final Year Project
            </p>
    </div>
    </div>
</body>
</html>
