@props(['title', 'subtitle'])

<div class="flex flex-col justify-between gap-4 border-b border-slate-100 pb-5 md:flex-row md:items-end">
    <div>
        <p class="text-[10px] font-bold uppercase tracking-widest text-sky-600">{{ $subtitle }}</p>
        <h1 class="mt-1 text-xl font-extrabold tracking-tight text-slate-850">{{ $title }}</h1>
    </div>
    @if(auth()->user()->role !== 'viewer')
        <a href="{{ route('admin.layanan.create') }}" class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-3.5 py-2 text-xs font-bold rounded-lg shadow-2xs transition duration-150 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Input Layanan</span>
        </a>
    @endif
</div>
