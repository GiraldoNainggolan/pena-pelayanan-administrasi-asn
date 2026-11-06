@props(['recentLayanan'])

<!-- Layanan Terbaru -->
<div class="rounded-xl border border-slate-100 bg-white shadow-2xs overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-100 px-4.5 py-3.5 bg-slate-50/50">
        <h2 class="text-xs font-bold text-slate-805 uppercase tracking-wider">5 Konsultasi & Asistensi Terbaru</h2>
        @php $__prefix = auth()->user()->role === 'viewer' ? 'viewer' : 'admin'; @endphp
        <a href="{{ route($__prefix . '.layanan.index') }}" class="text-[10px] font-bold text-sky-600 hover:text-sky-700 transition flex items-center gap-1">
            Lihat Semua Data
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-2xs border-collapse">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50/30 text-4xs font-bold uppercase tracking-widest text-slate-400">
                    <th class="px-4.5 py-2.5">Nomor</th>
                    <th class="px-4.5 py-2.5">Tanggal & Jam</th>
                    <th class="px-4.5 py-2.5">Nama ASN / NIP</th>
                    <th class="px-4.5 py-2.5">Kecamatan</th>
                    <th class="px-4.5 py-2.5">Jenis Layanan</th>
                    <th class="px-4.5 py-2.5">Status Akhir</th>
                    <th class="px-4.5 py-2.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse ($recentLayanan as $layanan)
                    <tr class="hover:bg-slate-50/30 transition duration-150">
                        <td class="px-4.5 py-3 font-semibold text-slate-900 text-3xs font-mono">{{ $layanan->nomor_layanan }}</td>
                        <td class="px-4.5 py-3 text-slate-500 text-3xs">
                            <span class="font-bold block text-slate-750">{{ $layanan->tanggal->format('d M Y') }}</span>
                            <span class="text-slate-400 text-4xs block mt-0.5">{{ $layanan->jam }}</span>
                        </td>
                        <td class="px-4.5 py-3 text-slate-800">
                            <div class="font-bold text-slate-900">{{ $layanan->nama_asn }}</div>
                            <div class="text-4xs text-slate-405 font-mono mt-0.5">NIP. {{ $layanan->nip }}</div>
                        </td>
                        <td class="px-4.5 py-3 text-slate-600 text-3xs font-semibold">{{ $layanan->kecamatan ?: '-' }}</td>
                        <td class="px-4.5 py-3 text-slate-800 text-3xs font-semibold">{{ $layanan->layanan }}</td>
                        <td class="px-4.5 py-3">
                            <span class="inline-block px-2 py-0.5 text-4xs font-bold uppercase tracking-wider rounded-full 
                                @if($layanan->hasil_layanan === 'Selesai') bg-emerald-50 text-emerald-700 border border-emerald-200/50
                                @elseif(in_array($layanan->hasil_layanan, ['Sebagian', 'Menunggu BKPSDM', 'Menunggu BKN'])) bg-amber-50 text-amber-700 border border-amber-200/50
                                @else bg-rose-50 text-rose-700 border border-rose-200/50 @endif">
                                {{ $layanan->hasil_layanan }}
                            </span>
                        </td>
                        @php
                            $role = auth()->user()->role;
                            $prefix = $role === 'viewer' ? 'viewer' : 'admin';
                        @endphp
                        <td class="px-4.5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route($prefix . '.layanan.show', $layanan->id) }}" class="text-sky-600 hover:text-sky-850 transition inline-flex items-center justify-center p-1.5 hover:bg-sky-50 rounded-lg border border-transparent hover:border-sky-100" title="Detail Layanan">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4.5 py-8 text-center text-slate-400 text-3xs">Belum ada layanan tercatat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
