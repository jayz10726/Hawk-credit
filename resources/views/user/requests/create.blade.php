@extends('layouts.app')
@section('title','Apply for Credit')
@section('content')

<div class="max-w-2xl mx-auto" x-data="{
    step: 1,
    form: { amount: '', tenure: '', purpose: '', details: '' },
    get monthly() {
        if (!this.form.amount || !this.form.tenure) return 0;
        const r = 0.15/12, n = parseInt(this.form.tenure), p = parseFloat(this.form.amount);
        if (r === 0) return Math.round(p / n);
        return Math.round(p * (r * Math.pow(1+r,n)) / (Math.pow(1+r,n) - 1));
    },
    get total() { return this.monthly * parseInt(this.form.tenure || 0); },
    fmt(n) { return new Intl.NumberFormat().format(Math.round(n)); }
}">

    <div class="mb-8">
        <h1 class="text-2xl font-bold font-serif text-white">Apply for Credit</h1>
        <p class="text-slate-500 text-sm mt-1 font-mono">
            Step <span x-text="step"></span> of 3
        </p>
    </div>

    {{-- Eligibility Checklist --}}
    <div class="card p-6 mb-6">
        <h2 class="font-semibold font-serif text-white mb-4">
            Eligibility Check
        </h2>
        <div class="space-y-3">
            @foreach($eligibility['checks'] as $check)
            <div class="flex items-center gap-3">
                @if($check['passed'])
                <div class="w-6 h-6 rounded-full bg-emerald-900/40 border border-emerald-500/40
                            flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0
                             01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                             clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-sm text-emerald-400 font-mono">{{ $check['label'] }}</span>
                @else
                <div class="w-6 h-6 rounded-full bg-red-900/40 border border-red-500/40
                            flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293
                             a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                             11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                             clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-sm text-red-400 font-mono">{{ $check['label'] }}</span>
                @endif
            </div>
            @endforeach
        </div>

        @if(!$eligibility['eligible'])
        <div class="mt-5 p-4 bg-red-900/20 border border-red-700/40 rounded-xl">
            <p class="text-red-400 text-sm font-mono">
                ❌ {{ $eligibility['reason'] }}
            </p>
        </div>
        @else
        <div class="mt-5 p-4 bg-emerald-900/20 border border-emerald-700/40 rounded-xl">
            <p class="text-emerald-400 text-sm font-mono">
                ✅ You are eligible to apply. Available credit:
                <strong>KES {{ number_format($score->available_credit) }}</strong>
            </p>
        </div>
        @endif
    </div>

    {{-- Block form if not eligible --}}
    @if(!$eligibility['eligible'])
    <div class="card p-8 text-center">
        <div class="w-16 h-16 rounded-2xl bg-red-900/20 border border-red-700/20
                    flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667
                         1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34
                         16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold font-serif text-white mb-2">
            Not Eligible to Apply
        </h3>
        <p class="text-slate-500 text-sm font-mono mb-6">
            {{ $eligibility['reason'] }}
        </p>
        <a href="{{ route('user.dashboard') }}" class="btn-ghost">
            ← Back to Dashboard
        </a>
    </div>

    @else
    {{-- Step Progress --}}
    <div class="flex items-center mb-8">
        @foreach(['Loan Details', 'Review', 'Documents'] as $i => $label)
        <div class="flex items-center {{ $i < 2 ? 'flex-1' : '' }}">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full flex items-center justify-center
                            text-xs font-bold font-mono border-2 transition-all duration-300"
                     :class="step > {{ $i+1 }}
                        ? 'bg-emerald-500 border-emerald-500 text-white'
                        : step === {{ $i+1 }}
                        ? 'bg-blue-600 border-blue-600 text-white'
                        : 'bg-transparent border-slate-700 text-slate-600'">
                    <template x-if="step > {{ $i+1 }}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0
                                 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                    </template>
                    <template x-if="step <= {{ $i+1 }}">
                        <span>{{ $i+1 }}</span>
                    </template>
                </div>
                <span class="text-xs font-mono mt-1 transition-colors"
                      :class="step >= {{ $i+1 }} ? 'text-slate-300' : 'text-slate-600'">
                    {{ $label }}
                </span>
            </div>
            @if($i < 2)
            <div class="flex-1 h-px mx-3 transition-colors duration-300"
                 :class="step > {{ $i+1 }} ? 'bg-emerald-500' : 'bg-slate-800'">
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="card p-8">
        <form method="POST" action="{{ route('user.requests.store') }}"
              enctype="multipart/form-data">
            @csrf

            @error('eligibility')
            <div class="mb-5 p-4 bg-red-900/20 border border-red-700/40 rounded-xl text-red-400 text-sm font-mono">
                ❌ {{ $message }}
            </div>
            @enderror

            {{-- STEP 1: Loan Details --}}
            <div x-show="step===1" x-transition>
                <h2 class="text-lg font-semibold font-serif text-white mb-1">
                    Loan Details
                </h2>
                <p class="text-slate-500 text-sm mb-6 font-mono">
                    Available credit: KES {{ number_format($score->available_credit) }}
                </p>
                <div class="space-y-5">
                    <div>
                        <label class="label">Amount (KES)</label>
                        <input type="number" name="amount_requested"
                               x-model="form.amount"
                               min="1000" max="{{ $score->available_credit }}" step="500"
                               class="input" placeholder="e.g. 50000">
                        @error('amount_requested')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="label">Repayment Tenure</label>
                        <select name="tenure_months" x-model="form.tenure" class="input">
                            <option value="">Select tenure</option>
                            @foreach([3,6,12,18,24,36] as $m)
                            <option value="{{ $m }}">{{ $m }} months</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Purpose</label>
                        <select name="purpose" x-model="form.purpose" class="input">
                            <option value="">Select purpose</option>
                            @foreach(['Business Expansion','Education','Medical','Home Improvement','Asset Purchase','Other'] as $p)
                            <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Additional Details</label>
                        <textarea name="purpose_details" x-model="form.details"
                                  rows="3" class="input resize-none"
                                  placeholder="Describe how you will use this credit...">
                        </textarea>
                    </div>
                </div>
            </div>

            {{-- STEP 2: Review --}}
            <div x-show="step===2" x-transition>
                <h2 class="text-lg font-semibold font-serif text-white mb-1">
                    Review Your Application
                </h2>
                <p class="text-slate-500 text-sm mb-6 font-mono">
                    Estimated repayment breakdown at 15% p.a.
                </p>
                <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6 space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400 font-mono">Amount Requested</span>
                        <span class="font-bold font-mono text-white">
                            KES <span x-text="fmt(form.amount)"></span>
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400 font-mono">Tenure</span>
                        <span class="font-mono text-white">
                            <span x-text="form.tenure"></span> months
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-400 font-mono">Purpose</span>
                        <span class="font-mono text-white" x-text="form.purpose"></span>
                    </div>
                    <div class="border-t border-slate-700 pt-4 space-y-3">
                        <div class="flex justify-between items-baseline">
                            <span class="text-slate-400 font-mono text-sm">
                                Est. Monthly Payment
                            </span>
                            <span class="text-2xl font-bold font-mono text-gold">
                                KES <span x-text="fmt(monthly)"></span>
                            </span>
                        </div>
                        <div class="flex justify-between items-baseline">
                            <span class="text-slate-400 font-mono text-sm">
                                Est. Total Repayable
                            </span>
                            <span class="text-lg font-bold font-mono text-white">
                                KES <span x-text="fmt(total)"></span>
                            </span>
                        </div>
                        <div class="flex justify-between items-baseline">
                            <span class="text-slate-400 font-mono text-sm">
                                Est. Interest
                            </span>
                            <span class="font-mono text-amber-400">
                                KES <span x-text="fmt(total - form.amount)"></span>
                            </span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 font-mono">
                        * Estimated at 15% p.a. — actual rate set by your admin upon approval
                    </p>
                </div>
            </div>

            {{-- STEP 3: Documents --}}
            <div x-show="step===3" x-transition x-data="{ files: [] }">
                <h2 class="text-lg font-semibold font-serif text-white mb-1">
                    Supporting Documents
                </h2>
                <p class="text-slate-500 text-sm mb-6 font-mono">
                    Upload ID, payslip, bank statement (optional but recommended)
                </p>
                <div class="border-2 border-dashed border-slate-700 hover:border-gold/40
                            rounded-2xl p-8 text-center transition-colors duration-200"
                     @dragover.prevent @drop.prevent="files = [...$event.dataTransfer.files]">
                    <div class="w-12 h-12 rounded-xl bg-slate-800 flex items-center
                                justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011
                                     9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <label class="cursor-pointer">
                        <span class="text-sm text-slate-400">Drag files here or </span>
                        <span class="text-gold hover:text-amber-400 font-semibold text-sm">
                            browse
                        </span>
                        <input type="file" name="documents[]" multiple
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="hidden"
                               @change="files = [...$event.target.files]">
                    </label>
                    <p class="text-xs text-slate-600 font-mono mt-2">
                        PDF, JPG, PNG · Max 5MB each
                    </p>
                    <div x-show="files.length > 0" class="mt-4 text-left space-y-2">
                        <template x-for="(f, i) in files" :key="i">
                            <div class="flex items-center gap-3 bg-slate-800 rounded-lg px-3 py-2">
                                <svg class="w-4 h-4 text-gold flex-shrink-0" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112
                                         2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0
                                         01-2-2V4z"/>
                                </svg>
                                <span class="text-xs text-slate-300 font-mono truncate"
                                      x-text="f.name">
                                </span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-800/60">
                <button type="button" @click="step--"
                        x-show="step > 1" class="btn-ghost">
                    ← Back
                </button>
                <div x-show="step === 1 || step === 2">
                    <button type="button" @click="step++" class="btn-primary">
                        Continue →
                    </button>
                </div>
                <div x-show="step === 3">
                    <button type="submit" class="btn-gold">
                        Submit Application ✓
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif

</div>
@endsection