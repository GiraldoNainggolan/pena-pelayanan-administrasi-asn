@extends('layouts.app')

@section('title', 'Laporan - PENA')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col justify-between gap-2 border-b border-sky-100 pb-3 md:flex-row md:items-end">
        <div>
            <p class="text-4xs font-bold uppercase tracking-wider text-sky-600">Pelaporan & Analisis Data</p>
            <h1 class="mt-1 text-lg font-bold tracking-tight text-slate-900">Laporan Konsultasi Kepegawaian</h1>
        </div>
        <button onclick="window.print();" class="bg-white border border-sky-200 text-sky-700 px-3 py-1.5 text-center text-xs font-semibold hover:bg-sky-50 transition duration-150 flex items-center gap-1.5 no-print">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-14.326 0C3.768 7.28 3 8.215 3 9.296v6.299a2.25 2.25 0 0 0 2.25 2.25h1.091M9 10.125h6M9 13.125h6M9 16.125h3" />
            </svg>
            Cetak Laporan
        </button>
    </div>

    <!-- Filter Form -->
    <div class="border border-sky-100 bg-white p-4 shadow-3xs no-print">
        @php $__prefix = auth()->user()->role === 'viewer' ? 'viewer' : 'admin'; @endphp
        <form method="GET" action="{{ route($__prefix . '.laporan.index') }}" class="space-y-3">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-5">
                <!-- Filter Periode -->
                <div>
                    <label for="periode" class="block text-4xs font-bold text-slate-500 uppercase mb-0.5">Periode</label>
                    <select name="periode" id="periode" class="w-full border border-sky-100 px-2.5 py-1.5 text-xs focus:border-sky-500 focus:outline-none">
                        <option value="harian" {{ $periode === 'harian' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="mingguan" {{ $periode === 'mingguan' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulanan" {{ $periode === 'bulanan' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="triwulan" {{ $periode === 'triwulan' ? 'selected' : '' }}>Triwulan Ini</option>
                        <option value="semester" {{ $periode === 'semester' ? 'selected' : '' }}>Semester Ini</option>
                        <option value="tahunan" {{ $periode === 'tahunan' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>

                <!-- Filter Jenis Layanan -->
                <div>
                    <label for="aplikasi" class="block text-4xs font-bold text-slate-500 uppercase mb-0.5">Jenis Layanan</label>
                    <select name="aplikasi" id="aplikasi" class="w-full border border-sky-100 px-2.5 py-1.5 text-xs focus:border-sky-500 focus:outline-none">
                        <option value="">-- Semua Layanan --</option>
                        <option value="Layanan SIASN" {{ request('aplikasi') === 'Layanan SIASN' ? 'selected' : '' }}>Layanan SIASN</option>
                        <option value="Layanan SIJEMPOL" {{ request('aplikasi') === 'Layanan SIJEMPOL' ? 'selected' : '' }}>Layanan SIJEMPOL</option>
                        <option value="Layanan SITEMAN" {{ request('aplikasi') === 'Layanan SITEMAN' ? 'selected' : '' }}>Layanan SITEMAN</option>
                        <option value="Kenaikan Pangkat" {{ request('aplikasi') === 'Kenaikan Pangkat' ? 'selected' : '' }}>Kenaikan Pangkat</option>
                        <option value="Pensiun" {{ request('aplikasi') === 'Pensiun' ? 'selected' : '' }}>Pensiun</option>
                        <option value="Kenaikan Gaji Berkala" {{ request('aplikasi') === 'Kenaikan Gaji Berkala' ? 'selected' : '' }}>Kenaikan Gaji Berkala</option>
                        <option value="Mutasi / Promosi" {{ request('aplikasi') === 'Mutasi / Promosi' ? 'selected' : '' }}>Mutasi / Promosi</option>
                        <option value="Diklat / Tugas Belajar" {{ request('aplikasi') === 'Diklat / Tugas Belajar' ? 'selected' : '' }}>Diklat / Tugas Belajar</option>
                        <option value="Disiplin & Kesejahteraan" {{ request('aplikasi') === 'Disiplin & Kesejahteraan' ? 'selected' : '' }}>Disiplin & Kesejahteraan</option>
                        <option value="Lainnya" {{ request('aplikasi') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Filter Kecamatan -->
                <div>
                    <label for="kecamatan" class="block text-4xs font-bold text-slate-500 uppercase mb-0.5">Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="w-full border border-sky-100 px-2.5 py-1.5 text-xs focus:border-sky-500 focus:outline-none">
                        <option value="">-- Semua Kecamatan --</option>
                        @foreach(\App\Models\Kecamatan::orderBy('nama')->get() as $kec)
                            <option value="{{ $kec->nama }}" {{ request('kecamatan') === $kec->nama ? 'selected' : '' }}>{{ $kec->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status Hasil -->
                <div>
                    <label for="hasil" class="block text-4xs font-bold text-slate-500 uppercase mb-0.5">Status Hasil</label>
                    <select name="hasil" id="hasil" class="w-full border border-sky-100 px-2.5 py-1.5 text-xs focus:border-sky-500 focus:outline-none">
                        <option value="">-- Semua Status --</option>
                        <option value="Selesai" {{ request('hasil') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Sebagian" {{ request('hasil') === 'Sebagian' ? 'selected' : '' }}>Sebagian</option>
                        <option value="Menunggu BKPSDM" {{ request('hasil') === 'Menunggu BKPSDM' ? 'selected' : '' }}>Menunggu BKPSDM</option>
                        <option value="Menunggu BKN" {{ request('hasil') === 'Menunggu BKN' ? 'selected' : '' }}>Menunggu BKN</option>
                        <option value="Pending" {{ request('hasil') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Ditolak" {{ request('hasil') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Action buttons -->
                <div class="flex items-end gap-1.5">
                    <button type="submit" class="flex-1 bg-sky-600 text-white py-1.5 text-center text-xs font-semibold hover:bg-sky-700 transition">
                        Filter Laporan
                    </button>
                    <a href="{{ route($__prefix . '.laporan.index') }}" class="bg-slate-100 text-slate-700 px-3 py-1.5 text-center text-xs font-medium hover:bg-slate-200 transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Ringkasan Angka Rekapitulasi -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="border border-sky-100 bg-white p-4 shadow-3xs">
            <p class="text-4xs font-bold text-slate-500 uppercase tracking-wider">Total Hasil Filter</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $records->count() }}</p>
            <p class="text-4xs text-sky-600 font-medium">Layanan masuk kriteria</p>
        </div>
        <div class="border border-sky-100 bg-white p-4 shadow-3xs">
            <p class="text-4xs font-bold text-emerald-600 uppercase tracking-wider">Selesai (Tuntas)</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $records->where('hasil_layanan', 'Selesai')->count() }}</p>
            <p class="text-4xs text-slate-400">Terselesaikan</p>
        </div>
        <div class="border border-sky-100 bg-white p-4 shadow-3xs">
            <p class="text-4xs font-bold text-amber-600 uppercase tracking-wider">Sedang Diproses</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">
                {{ $records->whereIn('hasil_layanan', ['Sebagian', 'Menunggu BKPSDM', 'Menunggu BKN'])->count() }}
            </p>
            <p class="text-4xs text-slate-400">Dalam antrean verifikasi</p>
        </div>
        <div class="border border-sky-100 bg-white p-4 shadow-3xs">
            <p class="text-4xs font-bold text-rose-600 uppercase tracking-wider">Pending / Ditolak</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">
                {{ $records->whereIn('hasil_layanan', ['Pending', 'Ditolak'])->count() }}
            </p>
            <p class="text-4xs text-slate-400">Terkendala syarat</p>
        </div>
    </div>

    <!-- Visualisasi Grafik Laporan -->
    <div class="grid gap-4 lg:grid-cols-2 no-print">
        <!-- Grafik Tren Harian/Mingguan dalam Periode -->
        <div class="border border-sky-100 bg-white p-4 shadow-3xs">
            <h3 class="text-xs font-bold text-slate-900 border-b border-sky-50 pb-2 mb-3">Grafik Tren Layanan (Per Tanggal)</h3>
            <div class="relative h-48 flex items-center justify-center">
                <canvas id="trendChartCanvas"></canvas>
            </div>
        </div>

        <!-- Grafik Kategori Hasil Status -->
        <div class="border border-sky-100 bg-white p-4 shadow-3xs">
            <h3 class="text-xs font-bold text-slate-900 border-b border-sky-50 pb-2 mb-3">Persentase Hasil Layanan</h3>
            <div class="relative h-48 flex items-center justify-center">
                <canvas id="hasilChartCanvas"></canvas>
            </div>
        </div>

        <!-- Rekapitulasi Jenis Layanan (Kategori) -->
        <div class="border border-sky-100 bg-white p-4 shadow-3xs">
            <h3 class="text-xs font-bold text-sky-800 border-b border-sky-50 pb-2 mb-3">Distribusi Kategori Layanan</h3>
            <div class="space-y-2.5">
                @forelse($byAplikasi as $layananName => $count)
                    <div>
                        <div class="flex justify-between text-3xs font-semibold mb-0.5">
                            <span class="text-slate-700">{{ $layananName }}</span>
                            <span class="text-sky-700">{{ $count }} Layanan ({{ round(($count / max($records->count(), 1)) * 100, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5">
                            <div class="bg-sky-600 h-1.5" style="width: {{ ($count / max($records->count(), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-3xs text-slate-500 py-2 text-center">Belum ada klasifikasi layanan.</p>
                @endforelse
            </div>
        </div>

        <!-- Rekapitulasi Asal Kecamatan -->
        <div class="border border-sky-100 bg-white p-4 shadow-3xs">
            <h3 class="text-xs font-bold text-sky-800 border-b border-sky-50 pb-2 mb-3">Distribusi Wilayah Kecamatan</h3>
            <div class="space-y-2.5">
                @forelse($byKecamatan->take(6) as $kecName => $count)
                    <div>
                        <div class="flex justify-between text-3xs font-semibold mb-0.5">
                            <span class="text-slate-700">{{ $kecName ?: 'Lainnya / Dinas' }}</span>
                            <span class="text-sky-700">{{ $count }} Layanan ({{ round(($count / max($records->count(), 1)) * 100, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5">
                            <div class="bg-sky-500 h-1.5" style="width: {{ ($count / max($records->count(), 1)) * 100 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-3xs text-slate-500 py-2 text-center">Belum ada data kecamatan terdata.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tabel Data Rekapitulasi Laporan (Printable) -->
    <div class="border border-sky-100 bg-white shadow-3xs">
        <div class="border-b border-sky-100 px-4 py-3 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-900">Daftar Rincian Konsultasi Kepegawaian ({{ ucfirst($periode) }})</h2>
            <span class="text-4xs font-bold text-slate-500 uppercase tracking-wider">Total: {{ $records->count() }} Data</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-2xs">
                <thead>
                    <tr class="border-b border-sky-100 bg-sky-50/40 text-4xs font-bold uppercase tracking-wider text-sky-900">
                        <th class="px-4 py-2.5">No. Layanan</th>
                        <th class="px-4 py-2.5">Tanggal / Jam</th>
                        <th class="px-4 py-2.5">Nama ASN / NIP</th>
                        <th class="px-4 py-2.5">Kecamatan</th>
                        <th class="px-4 py-2.5">Unit Kerja / Sekolah</th>
                        <th class="px-4 py-2.5">Kategori Layanan</th>
                        <th class="px-4 py-2.5">Hasil Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-100">
                    @forelse ($records as $r)
                        <tr class="hover:bg-sky-50/20 transition">
                            <td class="px-4 py-2 font-semibold text-slate-900 font-mono text-3xs">{{ $r->nomor_layanan }}</td>
                            <td class="px-4 py-2 text-slate-500 text-3xs">
                                <div>{{ $r->tanggal->format('d M Y') }}</div>
                                <div class="text-4xs text-slate-400">{{ $r->jam }}</div>
                            </td>
                            <td class="px-4 py-2 text-slate-800">
                                <div class="font-bold">{{ $r->nama_asn }}</div>
                                <div class="text-4xs text-slate-500">NIP. {{ $r->nip }}</div>
                            </td>
                            <td class="px-4 py-2 text-slate-600 font-medium text-3xs">{{ $r->kecamatan ?: '-' }}</td>
                            <td class="px-4 py-2 text-slate-600 text-3xs">
                                <div class="truncate max-w-[150px]">{{ $r->nama_sekolah ?: $r->unit_kerja ?: '-' }}</div>
                            </td>
                            <td class="px-4 py-2 text-slate-700 text-3xs">
                                <div class="font-semibold">{{ $r->layanan }}</div>
                                <div class="text-4xs text-slate-400">{{ $r->sub_layanan }}</div>
                            </td>
                            <td class="px-4 py-2">
                                <span class="inline-block px-2 py-0.5 text-4xs font-bold uppercase tracking-wider 
                                    @if($r->hasil_layanan === 'Selesai') bg-emerald-100 text-emerald-800
                                    @elseif(in_array($r->hasil_layanan, ['Sebagian', 'Menunggu BKPSDM', 'Menunggu BKN'])) bg-amber-100 text-amber-800
                                    @else bg-rose-100 text-rose-800 @endif">
                                    {{ $r->hasil_layanan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-slate-400 text-3xs">Tidak ada rincian data layanan yang memenuhi kriteria filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Trend Chart (Line Chart Simulation)
        const trendLabels = {!! json_encode(array_keys($trend->toArray())) !!};
        const trendData = {!! json_encode(array_values($trend->toArray())) !!};

        new Chart(document.getElementById('trendChartCanvas'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Jumlah Layanan',
                    data: trendData,
                    borderColor: '#0284c7', // sky-600
                    backgroundColor: 'rgba(2, 132, 199, 0.05)',
                    borderWidth: 1.5,
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { size: 9 }
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 9 }
                        }
                    }
                }
            }
        });

        // Hasil/Status Chart (Doughnut Chart)
        const hasilLabels = {!! json_encode(array_keys($byHasil->toArray())) !!};
        const hasilData = {!! json_encode(array_values($byHasil->toArray())) !!};

        new Chart(document.getElementById('hasilChartCanvas'), {
            type: 'doughnut',
            data: {
                labels: hasilLabels,
                datasets: [{
                    data: hasilData,
                    backgroundColor: [
                        '#10b981', // emerald-500 (Selesai)
                        '#f59e0b', // amber-500 (Sebagian / Menunggu)
                        '#f43f5e', // rose-500 (Pending / Ditolak)
                        '#6366f1', // indigo-500
                        '#8b5cf6', // violet-500
                        '#64748b'  // slate-500
                    ],
                    borderWidth: 1,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 9
                            },
                            boxWidth: 10
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
