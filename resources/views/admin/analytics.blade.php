@extends('layouts.app')
@section('title','Analytics')
@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold font-serif text-white">Portfolio Analytics</h1>
    <p class="text-slate-500 text-sm mt-1 font-mono">{{ auth()->user()->organization?->name }}</p>
</div>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
<p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Collection Rate</p>
        <p class='text-2xl font-bold font-mono @if($data["collection_rate"] >= 80) text-emerald-400 @else text-red-400 @endif'>
            {{ $data['collection_rate'] }}%
        </p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Overdue Installments</p>
        <p class='text-2xl font-bold font-mono @if($data["overdue_installments"] > 0) text-red-400 @else text-emerald-400 @endif'>
            {{ $data['overdue_installments'] }}
        </p>
    </div>
    <div class="stat-card col-span-2">
        <p class="text-xs font-mono text-slate-500 uppercase tracking-widest mb-3">Score Distribution</p>
        <div class="flex gap-3 flex-wrap">
            @foreach($data['score_distribution'] as $dist)
            <span class='badge-blue font-mono'>{{ ucfirst($dist->band) }}: {{ $dist->count }}</span>
            @endforeach
        </div>
    </div>
</div>
<div class="grid lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-5">Monthly Repayments (Last 12 Months)</h2>
        <canvas id="repaymentsChart" height="80"></canvas>
    </div>
    <div class="card p-6">
        <h2 class="font-semibold font-serif text-white mb-5">Credit Score Distribution</h2>
        <canvas id="scoreDistChart"></canvas>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const repayData = @json($data['monthly_repayments']);
new Chart(document.getElementById('repaymentsChart'), {
    type:'bar',
    data: { labels: repayData.map(d=>d.month),
            datasets:[{ label:'Repayments (KES)', data:repayData.map(d=>d.total),
              backgroundColor:'rgba(212,160,23,0.4)', borderColor:'#D4A017', borderWidth:2 }] },
    options:{ responsive:true, plugins:{legend:{labels:{color:'#94a3b8'}}},
      scales:{ y:{grid:{color:'#1e293b'},ticks:{color:'#64748b'}},x:{grid:{display:false},ticks:{color:'#64748b'}} } }
});
const distData = @json($data['score_distribution']);
new Chart(document.getElementById('scoreDistChart'), {
    type:'doughnut',
    data: { labels: distData.map(d=>d.band),
            datasets:[{ data:distData.map(d=>d.count),
              backgroundColor:['#10b981','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#64748b'],
              borderColor:'#0F172A', borderWidth:3 }] },
    options:{ responsive:true, plugins:{legend:{position:'bottom',labels:{color:'#94a3b8',padding:20}}} }
});
</script>
@endpush
@endsection
