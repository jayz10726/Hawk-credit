@extends('layouts.app')
@section('title','Audit Logs')
@section('content')
<div class="mb-8"><h1 class="text-2xl font-bold font-serif text-white">Audit Logs</h1>
    <p class="text-slate-500 text-sm mt-1 font-mono">Complete trail of every action in the system</p></div>
<form method="GET" class="flex gap-3 mb-6 flex-wrap">
<input type="text" name="event" value="{{ request('event') }}" placeholder="Filter by event..."
           class="input w-56">
    <button class="btn-primary">Search</button>
    <a href="{{ route('super.audit-logs') }}" class='btn-ghost'>Clear</a>
</form>
<div class="card overflow-hidden">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead class="table-head"><tr>
            <th class="text-left px-5 py-3">Time</th>
            <th class="text-left px-5 py-3">User</th>
            <th class="text-left px-5 py-3">Event</th>
            <th class="text-left px-5 py-3">Record</th>
            <th class="text-left px-5 py-3">IP Address</th>
        </tr></thead>
        <tbody>
            @forelse($logs as $log)
            <tr class="table-row">
                <td class="px-5 py-3 text-xs font-mono text-slate-500">{{ $log->created_at->diffForHumans() }}</td>
                <td class="px-5 py-3"><p class="text-white text-sm font-medium">{{ $log->user?->full_name ?? "System" }}</p></td>
                <td class="px-5 py-3"><span class="badge-blue font-mono text-xs">{{ $log->event }}</span></td>
                <td class='px-5 py-3 text-xs font-mono text-slate-400'>{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                <td class="px-5 py-3 text-xs font-mono text-slate-500">{{ $log->ip_address }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-16 text-center text-slate-600 font-mono">No audit logs yet.</td></tr>
            @endforelse
        </tbody></table></div>
    <div class="px-5 py-4 border-t border-slate-800/40">{{ $logs->links() }}</div>
</div>
@endsection
