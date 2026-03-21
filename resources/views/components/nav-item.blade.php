@props(['href', 'active' => false])
<a href="{{ $href }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group
          {{ $active
              ? 'bg-blue-600/20 text-blue-400 border border-blue-500/20'
              : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 border border-transparent' }}"
   @if($active) aria-current="page" @endif>
    <span class="flex-shrink-0 {{ $active ? 'text-blue-400' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors">
        {{ $icon }}
    </span>
    <span>{{ $slot }}</span>
    @if($active)
    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></span>
    @endif
</a>
