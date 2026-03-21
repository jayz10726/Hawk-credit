@extends('layouts.app')
@section('title','My Credit Score')
@section('content')
<div class="grid lg:grid-cols-3 gap-6">
    {{-- Score Gauge --}}
    <div class="lg:col-span-1 card p-6 flex flex-col items-center">
        <h2 class="font-semibold font-serif text-white mb-1 self-start">Credit Score</h2>
        <p class="text-xs font-mono text-slate-500 self-start mb-6">Last updated: {{ $score?->last_calculated_at?->diffForHumans() ?? 'Never' }}</p>
        <div class="relative w-52 h-28 mb-4">
            <canvas id="gaugeChart"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-end pb-2">
                <p class='text-4xl font-bold font-mono @if($score?->score >= 660) text-emerald-400 @elseif($score?->score >= 540) text-amber-400 @else text-red-400 @endif'>
                    {{ $score?->score ?? 300 }}
                </p>
                <p class="text-xs font-mono text-slate-500">/ 850</p>
            </div>
        </div>
        <span class='px-4 py-1.5 rounded-full text-sm font-semibold font-mono @if(in_array($score?->band,["exceptional","excellent"])) badge-green 
@elseif($score?->band==="good") badge-blue @elseif($score?->band==="fair") badge-yellow @else badge-red @endif'>
            {{ ucfirst($score?->band ?? 'very_poor') }}
        </span>
        {{-- Score bands --}}
        <div class="w-full mt-6 space-y-2">
            @foreach([
                ['Exceptional','780-850','bg-emerald-500'],
                ['Excellent','720-779','bg-emerald-400'],
                ['Good','660-719','bg-blue-400'],
                ['Fair','600-659','bg-amber-400'],
                ['Poor','540-599','bg-orange-400'],
                ['Very Poor','300-539','bg-red-500'],
            ] as [$band,$range,$dot])
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ $dot }}"></span>
                    <span class="text-slate-400 font-mono">{{ $band }}</span>
                </div>
                <span class="text-slate-600 font-mono">{{ $range }}</span>
            </div>
            @endforeach
        </div>
    </div>
    {{-- Factors + History --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="card p-6">
            <h2 class="font-semibold font-serif text-white mb-5">Score Factors</h2>
            <div class="space-y-5">
                @php
                    $total = ($score->on_time_payments + $score->late_payments + $score->missed_payments) ?: 1;
                    $factors = [
                        ['Repayment History','40%', min(100,round(($score->on_time_payments/$total)*100)),'text-emerald-400','bg-emerald-500'],
                        ['Credit Utilization','25%', $score->credit_limit > 0 ? max(0,round((1 - ($score->credit_limit - $score->available_credit)/$score->credit_limit)*100)) : 0,'text-blue-400','bg-blue-500'],
                        ['Payment Timeliness','10%', max(0,round((1 - $score->late_payments * 0.1 - $score->missed_payments * 0.3)*100)),'text-amber-400','bg-amber-500'],
                    ];
                @endphp
                @foreach($factors as [$name,$weight,$val,$col,$bar])
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <span class="text-sm font-medium text-slate-300">{{ $name }}</span>
                            <span class="text-xs font-mono text-slate-600 ml-2">Weight: {{ $weight }}</span>
                        </div>
                        <span class="font-bold font-mono text-sm {{ $col }}">{{ $val }}%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $bar }} transition-all duration-700"
                             style="width:{{ $val }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        {{-- History Chart --}}
        <div class="card p-6">
            <h2 class="font-semibold font-serif text-white mb-5">Score History</h2>
            <canvas id="historyChart" height="80"></canvas>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const score = {{ $score?->score ?? 300 }};
    const pct   = (score - 300) / 550;
    const col   = score >= 660 ? '#10b981' : score >= 540 ? '#f59e0b' : '#ef4444';
    new Chart(document.getElementById('gaugeChart'), {
        type: 'doughnut',
        data: { datasets: [{ data: [pct, 1-pct], backgroundColor: [col, '#1e293b'],
                             borderWidth: 0, circumference: 180, rotation: -90 }] },
        options: { cutout: '80%', plugins: { legend: { display: false }, tooltip: { enabled: false } } }
    });
    const hist = @json($history);
    new Chart(document.getElementById('historyChart'), {
        type: 'line',
        data: {
            labels: hist.map(h => h.created_at?.substring(0,10) ?? ''),
            datasets: [{ label: 'Score', data: hist.map(h => h.new_score),
                        borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)',
                        borderWidth: 2.5, fill: true, tension: 0.4,
                        pointBackgroundColor: '#3b82f6', pointRadius: 4 }]
        },
        options: { responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { min:300, max:850, grid: { color:'#1e293b' },
                           ticks: { color:'#64748b', font: { family:'JetBrains Mono' } } },
                      x: { grid: { display:false }, ticks: { color:'#64748b', font: { family:'JetBrains Mono' } } } }
        }
    });
</script>
@endpush
@endsection
