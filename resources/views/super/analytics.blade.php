@extends('layouts.app')
@section('title','Global Analytics')
@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold font-serif text-white">Global Analytics</h1>
    <p class="text-slate-500 text-sm mt-1 font-mono">System-wide financial performance</p>
</div>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Total Disbursed</p>
        <p class="text-2xl font-bold font-mono text-white">KES {{ number_format($data['total_disbursed']/1000) }}K</p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Total Collected</p>
        <p class="text-2xl font-bold font-mono text-emerald-400">KES {{ number_format($data['total_collected']/1000) }}K</p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Default Rate</p>
        <p class='text-2xl font-bold font-mono @if($data["default_rate"] > 10) text-red-400 @else text-emerald-400 @endif'>
            {{ $data['default_rate'] }}%
        </p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Collection Rate</p>
        <p class="text-2xl font-bold font-mono text-blue-400">
            @php $cr = $data['total_disbursed'] > 0 ? round(($data['total_collected']/$data['total_disbursed'])*100,1) : 0 @endphp
            {{ $cr }}%
        </p>
    </div>
</div>
<div class="grid lg:grid-cols-2 gap-6 mb-6">
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-5">Monthly Disbursements vs Collections</h2>
        <canvas id="disbChart" height="80"></canvas>
    </div>
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-5">Organization Performance</h2>
        <div class="space-y-4">
            @forelse($data['org_performance'] as $org)
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-900/40 flex items-center justify-center">
 <span class="text-blue-400 text-xs font-bold">{{ strtoupper(substr($org->name,0,2)) }}</span>
                    </div>
                    <span class="text-slate-300">{{ $org->name }}</span>
                </div>
                <div class="text-right">
                    <span class="font-mono font-semibold text-white">KES {{ number_format($org->total_disbursed ?? 0) }}</span>
                    <span class="text-xs text-slate-500 block font-mono">{{ $org->users_count }} members</span>
                </div>
            </div>
            @empty
            <p class="text-slate-500 text-sm font-mono">No data yet.</p>
            @endforelse
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const disbData = @json($data['monthly_disbursements']);
const collData = @json($data['monthly_repayments']);
const allMonths = [...new Set([...disbData.map(d=>d.month),...collData.map(d=>d.month)])].sort();
new Chart(document.getElementById('disbChart'), {
    type:'bar',
    data: {
        labels: allMonths,
        datasets: [
            { label:'Disbursed', data: allMonths.map(m=>disbData.find(d=>d.month===m)?.total||0),
              backgroundColor:'rgba(59,130,246,0.5)', borderColor:'#3b82f6', borderWidth:2 },
            { label:'Collected', data: allMonths.map(m=>collData.find(d=>d.month===m)?.total||0),
              backgroundColor:'rgba(16,185,129,0.5)', borderColor:'#10b981', borderWidth:2 }
        ]
    },
    options: { responsive:true, plugins:{legend:{labels:{color:'#94a3b8',font:{family:'JetBrains Mono'}}}},
        scales:{ y:{grid:{color:'#1e293b'},ticks:{color:'#64748b',font:{family:'JetBrains Mono'}}},
                 x:{grid:{display:false},ticks:{color:'#64748b',font:{family:'JetBrains Mono'}}} } }
});
</script>
@endpush
@endsection
