@php
    $prefix = auth()->user()->role === 'viewer' ? 'viewer' : 'admin';
@endphp

<!-- Header Navbar Component -->
<header class="border-b border-slate-200/60 bg-white/80 backdrop-blur-md sticky top-0 z-20 no-print">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="h-6 w-6 rounded-md bg-sky-100/50 text-sky-600 flex items-center justify-center border border-sky-200/50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751h-.152c-3.196 0-6.1-1.249-8.25-3.286Zm0 3.036h.008v.008H12V5.75Z" />
                </svg>
            </div>
            <span class="text-[11px] font-extrabold text-slate-700 tracking-wide">PENA &mdash; Pelayanan dan Administrasi ASN</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route($prefix . '.profile') }}" class="text-[11px] font-semibold text-slate-500 hover:text-sky-600 transition flex items-center gap-1.5 bg-slate-50 hover:bg-sky-50 px-2.5 py-1.5 rounded-md border border-slate-100 hover:border-sky-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                Profil Saya
            </a>
        </div>
    </div>
</header>
