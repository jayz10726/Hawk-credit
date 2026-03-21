@extends('layouts.app')
@section('title','System Health')
@section('content')
<div class="mb-8"><h1 class="text-2xl font-bold font-serif text-white">System Health</h1>
    <p class="text-slate-500 text-sm mt-1 font-mono">Database stats, versions, and recent activity</p></div>
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-5">System Information</h2>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between items-center py-2 border-b border-slate-800/40">
                <span class="text-slate-400 font-mono">PHP Version</span>
                <span class="badge-green font-mono">{{ $health['php_version'] }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-slate-800/40">
                <span class="text-slate-400 font-mono">Laravel Version</span>
                <span class="badge-blue font-mono">{{ $health['laravel_version'] }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-slate-800/40">
                <span class="text-slate-400 font-mono">Environment</span>
  <span class="badge-yellow font-mono capitalize">{{ $health['environment'] }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-slate-400 font-mono">Database Size</span>
                <span class="font-mono text-white">{{ $health['db_size'] }}</span>
            </div>
        </div>
    </div>
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-5">Database Records</h2>
        <div class="space-y-3">
        @foreach($health['total_records'] as $table => $count)
        <div class="flex justify-between items-center">
            <span class="text-slate-400 font-mono text-sm capitalize">{{ $table }}</span>
            <div class="flex items-center gap-3">
                <div class="w-32 bg-slate-800 rounded-full h-1.5">
                    <div class="bg-blue-500 h-1.5 rounded-full" style="width:{{ min(100, ($count/max(1,max($health['total_records']))) * 100) }}%"></div>
                </div>
                <span class="font-mono font-bold text-white w-12 text-right">{{ number_format($count) }}</span>
            </div>
        </div>
        @endforeach
        </div>
    </div>
</div>
<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-800/60"><h2 class="font-semibold font-serif text-white">Recent Activity</h2></div>
    <div class="divide-y divide-slate-800/40">
        @foreach($health['recent_activity'] as $log)
        <div class="flex items-center gap-4 px-6 py-3">
            <div class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></div>
            <span class="text-xs font-mono text-slate-500 w-32 flex-shrink-0">{{ $log->created_at->diffForHumans() }}</span>
            <span class="badge-blue text-xs font-mono">{{ $log->event }}</span>
            <span class="text-sm text-slate-300">{{ $log->user?->full_name ?? "System" }}</span>
        </div>
        @endforeach
    </div>
</div>
@endsection
