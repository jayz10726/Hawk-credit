@props(['href', 'active' => false])
<a href="{{ $href }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group
          {{ $active
                ? 'bg-blue-600 text-white shadow-sm'
                : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    <span class="text-base leading-none {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}">
        {{ $icon }}
    </span>
    {{ $slot }}
</a>
