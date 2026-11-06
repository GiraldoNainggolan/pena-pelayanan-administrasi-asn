@props(['kecamatanChart', 'sekolahChart'])

<!-- Grafik dan Visualisasi Data -->
<div class="grid gap-6 lg:grid-cols-2">
    <!-- Grafik Kategori Layanan -->
    <div class="rounded-xl border border-slate-100 bg-white p-4 shadow-2xs">
        <h3 class="text-xs font-bold text-slate-805 border-b border-slate-100 pb-2.5 mb-3.5 uppercase tracking-wider">Grafik Jenis Layanan Kepegawaian</h3>
        <div class="relative h-48 flex items-center justify-center">
            <canvas id="layananChartCanvas"></canvas>
        </div>
    </div>

    <!-- Grafik Tren Bulanan -->
    <div class="rounded-xl border border-slate-100 bg-white p-4 shadow-2xs">
        <h3 class="text-xs font-bold text-slate-805 border-b border-slate-100 pb-2.5 mb-3.5 uppercase tracking-wider">Tren Layanan Bulanan</h3>
        <div class="relative h-48 flex items-center justify-center">
            <canvas id="bulanChartCanvas"></canvas>
        </div>
    </div>

    <!-- Rekap Kecamatan (Top 5) -->
    <div class="rounded-xl border border-slate-100 bg-white p-4 shadow-2xs">
        <h3 class="text-xs font-bold text-slate-805 border-b border-slate-100 pb-2.5 mb-3.5 uppercase tracking-wider">Top 5 Asal Kecamatan Terbanyak</h3>
        <div class="space-y-3">
            @forelse($kecamatanChart->take(5) as $kc)
                <div class="space-y-1">
                    <div class="flex justify-between text-3xs font-semibold">
                        <span class="text-slate-600 font-bold">{{ $kc->kecamatan ?: 'Lainnya / Dinas / Sekretariat' }}</span>
                        <span class="text-sky-600 font-mono font-bold">{{ $kc->total }} Layanan</span>
                    </div>
                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        @php
                            $maxKec = $kecamatanChart->first()->total ?? 1;
                            $percentage = ($kc->total / $maxKec) * 100;
                        @endphp
                        <div class="bg-sky-500 h-full rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-3xs text-slate-500 py-3 text-center">Belum ada data kecamatan.</p>
            @endforelse
        </div>
    </div>

    <!-- Rekap Sekolah (Top 5) -->
    <div class="rounded-xl border border-slate-100 bg-white p-4 shadow-2xs">
        <h3 class="text-xs font-bold text-slate-805 border-b border-slate-100 pb-2.5 mb-3.5 uppercase tracking-wider">Top 5 Asal Sekolah Terbanyak</h3>
        <div class="space-y-3">
            @forelse($sekolahChart->take(5) as $sc)
                <div class="space-y-1">
                    <div class="flex justify-between text-3xs font-semibold">
                        <span class="text-slate-600 font-bold truncate max-w-[240px] inline-block">{{ $sc->nama_sekolah }}</span>
                        <span class="text-sky-600 font-mono font-bold shrink-0">{{ $sc->total }} Layanan</span>
                    </div>
                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        @php
                            $maxSek = $sekolahChart->first()->total ?? 1;
                            $percentage = ($sc->total / $maxSek) * 100;
                        @endphp
                        <div class="bg-sky-500 h-full rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-3xs text-slate-500 py-3 text-center">Belum ada data sekolah.</p>
            @endforelse
        </div>
    </div>
</div>
