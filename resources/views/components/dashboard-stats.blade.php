@props(['today', 'month', 'year', 'selesai', 'diproses', 'menunggu'])

<!-- Statistik Utama Cards -->
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <!-- Hari Ini Card -->
    <div class="rounded-xl border border-slate-100 bg-white p-4 shadow-2xs flex items-center justify-between hover:shadow-xs transition duration-150">
        <div>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Layanan Hari Ini</p>
            <p class="text-2xl font-black text-slate-800 mt-1 leading-none">{{ $today }}</p>
            <p class="text-[9px] text-sky-600 font-semibold flex items-center gap-1 mt-1.5">
                <span class="h-1 w-1 rounded-full bg-sky-500 animate-pulse"></span>
                Konsultasi hari ini
            </p>
        </div>
        <div class="h-9 w-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-100/50 shrink-0">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
        </div>
    </div>

    <!-- Bulan Ini Card -->
    <div class="rounded-xl border border-slate-100 bg-white p-4 shadow-2xs flex items-center justify-between hover:shadow-xs transition duration-150">
        <div>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Layanan Bulan Ini</p>
            <p class="text-2xl font-black text-slate-800 mt-1 leading-none">{{ $month }}</p>
            <p class="text-[9px] text-slate-400 font-semibold mt-1.5">Total bulan berjalan</p>
        </div>
        <div class="h-9 w-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-100/50 shrink-0">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
        </div>
    </div>

    <!-- Tahun Ini Card -->
    <div class="rounded-xl border border-slate-100 bg-white p-4 shadow-2xs flex items-center justify-between hover:shadow-xs transition duration-150">
        <div>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Layanan Tahun Ini</p>
            <p class="text-2xl font-black text-slate-800 mt-1 leading-none">{{ $year }}</p>
            <p class="text-[9px] text-slate-400 font-semibold mt-1.5">Akumulasi tahun berjalan</p>
        </div>
        <div class="h-9 w-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center border border-sky-100/50 shrink-0">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" /></svg>
        </div>
    </div>
</div>

<!-- Status Tindak Lanjut Badge Grid -->
<div class="grid gap-3 sm:grid-cols-3">
    <!-- Selesai -->
    <div class="rounded-xl border border-emerald-100 bg-emerald-50/20 p-3.5 flex items-center gap-3 shadow-2xs">
        <div class="rounded-lg bg-emerald-100/60 text-emerald-800 p-2 border border-emerald-200/30 shrink-0">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
        </div>
        <div>
            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Selesai / Tuntas</p>
            <p class="text-xl font-black text-slate-800 leading-tight">{{ $selesai }}</p>
        </div>
    </div>

    <!-- Sedang Diproses -->
    <div class="rounded-xl border border-amber-100 bg-amber-50/20 p-3.5 flex items-center gap-3 shadow-2xs">
        <div class="rounded-lg bg-amber-100/60 text-amber-800 p-2 border border-amber-200/30 shrink-0">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
        </div>
        <div>
            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Sedang Diproses</p>
            <p class="text-xl font-black text-slate-800 leading-tight">{{ $diproses }}</p>
        </div>
    </div>

    <!-- Pending / Ditolak -->
    <div class="rounded-xl border border-rose-100 bg-rose-50/20 p-3.5 flex items-center gap-3 shadow-2xs">
        <div class="rounded-lg bg-rose-100/60 text-rose-800 p-2 border border-rose-200/30 shrink-0">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
        </div>
        <div>
            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Pending / Ditolak</p>
            <p class="text-xl font-black text-slate-800 leading-tight">{{ $menunggu }}</p>
        </div>
    </div>
</div>
