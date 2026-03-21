@if ($paginator->hasPages())
<nav class="flex items-center justify-between" aria-label="Pagination">
    <div class="text-xs font-mono text-slate-500">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }}
    </div>
    <div class="flex items-center gap-1">
        @if($paginator->onFirstPage())
        <span class="px-3 py-1.5 text-xs font-mono text-slate-700 bg-slate-900 border border-slate-800 rounded-lg cursor-not-allowed">← Prev</span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 text-xs font-mono text-slate-400 bg-slate-900 border border-slate-700 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">← Prev</a>
        @endif
        @foreach($elements as $element)
            @if(is_string($element))
            <span class="px-3 py-1.5 text-xs font-mono text-slate-600">{{ $element }}</span>
            @endif
            @if(is_array($element))
                @foreach($element as $page => $url)
                    @if($page == $paginator->currentPage())
                    <span class="px-3 py-1.5 text-xs font-bold font-mono text-hawk-950 bg-gold rounded-lg">{{ $page }}</span>
   <a href="{{ $url }}" class="px-3 py-1.5 text-xs font-mono text-slate-400 bg-slate-900 border border-slate-700 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 text-xs font-mono text-slate-400 bg-slate-900 border border-slate-700 rounded-lg hover:bg-slate-800 hover:text-white transition-colors">Next →</a>
        @else
        <span class="px-3 py-1.5 text-xs font-mono text-slate-700 bg-slate-900 border border-slate-800 rounded-lg cursor-not-allowed">Next →</span>
        @endif
    </div>
</nav>
@endif
