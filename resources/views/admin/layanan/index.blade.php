@extends('layouts.app')

@section('title', 'Data Layanan - PENA')

@section('content')
<div class="space-y-3">
    <!-- Header -->
    <div class="flex flex-col justify-between gap-2 border-b border-sky-100 pb-2 md:flex-row md:items-end">
        <div>
            <p class="text-slate-500 uppercase font-bold tracking-wider" style="font-size: 8px;">Pelayanan Konsultasi Kepegawaian</p>
            <h1 class="mt-0.5 text-base font-bold tracking-tight text-slate-900">Daftar Asistensi Kepegawaian</h1>
        </div>
        @php $__prefix = auth()->user()->role === 'viewer' ? 'viewer' : 'admin'; @endphp
        <div class="flex flex-wrap gap-2">
            @if(auth()->user()->role !== 'viewer')
                <!-- Export Excel -->
                <a href="{{ route('admin.export.excel', request()->all()) }}" class="bg-white border border-sky-200 text-sky-700 px-2.5 py-1 text-center text-3xs font-semibold hover:bg-sky-50 transition duration-150 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Excel
                </a>
            @endif
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                <a href="{{ route('admin.layanan.create') }}" class="bg-sky-600 text-white px-3 py-1 text-center text-3xs font-semibold hover:bg-sky-700 transition duration-150">
                    + Tambah Layanan
                </a>
            @endif
        </div>
    </div>

    <!-- Pencarian & Filter -->
    <div class="border border-sky-100 bg-white p-3 shadow-3xs">
        <form method="GET" action="{{ route($__prefix . '.layanan.index') }}" class="space-y-2">
            <div class="grid gap-2 md:grid-cols-3 lg:grid-cols-4">
                <!-- Search Text -->
                <div class="md:col-span-2">
                    <label class="block font-bold text-slate-500 uppercase mb-0.5" style="font-size: 8px;">Cari ASN (Nama, NIP, NIK, No. Layanan)</label>
                    <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Ketik kata kunci..." class="w-full border border-sky-100 px-2 py-1 text-3xs focus:border-sky-500 focus:outline-none">
                </div>

                <!-- Filter Tanggal -->
                <div>
                    <label class="block font-bold text-slate-500 uppercase mb-0.5" style="font-size: 8px;">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}" class="w-full border border-sky-100 px-2 py-1 text-3xs focus:border-sky-500 focus:outline-none">
                </div>

                <!-- Filter Jenis Layanan -->
                <div>
                    <label class="block font-bold text-slate-500 uppercase mb-0.5" style="font-size: 8px;">Jenis Layanan</label>
                    <select name="layanan" id="layanan" class="w-full border border-sky-100 px-2 py-1 text-3xs focus:border-sky-500 focus:outline-none">
                        <option value="">-- Semua Layanan --</option>
                        <option value="Kenaikan Pangkat" {{ request('layanan') === 'Kenaikan Pangkat' ? 'selected' : '' }}>Kenaikan Pangkat</option>
                        <option value="Pensiun" {{ request('layanan') === 'Pensiun' ? 'selected' : '' }}>Pensiun</option>
                        <option value="Kenaikan Gaji Berkala" {{ request('layanan') === 'Kenaikan Gaji Berkala' ? 'selected' : '' }}>Kenaikan Gaji Berkala</option>
                        <option value="Mutasi / Promosi" {{ request('layanan') === 'Mutasi / Promosi' ? 'selected' : '' }}>Mutasi / Promosi</option>
                        <option value="Diklat / Tugas Belajar" {{ request('layanan') === 'Diklat / Tugas Belajar' ? 'selected' : '' }}>Diklat / Tugas Belajar</option>
                        <option value="Disiplin & Kesejahteraan" {{ request('layanan') === 'Disiplin & Kesejahteraan' ? 'selected' : '' }}>Disiplin & Kesejahteraan</option>
                        <option value="Lainnya" {{ request('layanan') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Filter Kecamatan -->
                <div>
                    <label class="block font-bold text-slate-500 uppercase mb-0.5" style="font-size: 8px;">Kecamatan</label>
                    <select name="kecamatan" id="kecamatan" class="w-full border border-sky-100 px-2 py-1 text-3xs focus:border-sky-500 focus:outline-none">
                        <option value="">-- Semua Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->nama }}" {{ request('kecamatan') === $kec->nama ? 'selected' : '' }}>{{ $kec->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Sekolah -->
                <div>
                    <label class="block font-bold text-slate-500 uppercase mb-0.5" style="font-size: 8px;">Sekolah</label>
                    <select name="sekolah" id="sekolah" class="w-full border border-sky-100 px-2 py-1 text-3xs focus:border-sky-500 focus:outline-none">
                        <option value="">-- Semua Sekolah --</option>
                        @foreach($sekolahs as $sek)
                            <option value="{{ $sek->nama }}" {{ request('sekolah') === $sek->nama ? 'selected' : '' }}>{{ $sek->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Hasil / Status -->
                <div>
                    <label class="block font-bold text-slate-500 uppercase mb-0.5" style="font-size: 8px;">Status Hasil</label>
                    <select name="hasil" id="hasil" class="w-full border border-sky-100 px-2 py-1 text-3xs focus:border-sky-500 focus:outline-none">
                        <option value="">-- Semua Status --</option>
                        <option value="Selesai" {{ request('hasil') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Sebagian" {{ request('hasil') === 'Sebagian' ? 'selected' : '' }}>Sebagian</option>
                        <option value="Menunggu BKPSDM" {{ request('hasil') === 'Menunggu BKPSDM' ? 'selected' : '' }}>Menunggu BKPSDM</option>
                        <option value="Menunggu BKN" {{ request('hasil') === 'Menunggu BKN' ? 'selected' : '' }}>Menunggu BKN</option>
                        <option value="Pending" {{ request('hasil') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Ditolak" {{ request('hasil') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="flex items-end gap-1.5">
                    <button type="submit" class="flex-1 bg-sky-600 text-white py-1 text-center text-3xs font-semibold hover:bg-sky-700 transition">
                        Filter
                    </button>
                    <a href="{{ route($__prefix . '.layanan.index') }}" class="bg-slate-100 text-slate-700 px-2 py-1 text-center text-3xs font-medium hover:bg-slate-200 transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Data Layanan (Compact Design) -->
    <div class="border border-sky-100 bg-white shadow-3xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left" style="font-size: 9px; line-height: 1.2; border-collapse: collapse;">
                <thead>
                    <tr class="border-b border-sky-100 bg-sky-50/40 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="px-2 py-1.5 whitespace-nowrap" style="font-size: 7.5px;">Nomor Layanan</th>
                        <th class="px-2 py-1.5 whitespace-nowrap" style="font-size: 7.5px;">Tanggal / Jam</th>
                        <th class="px-2 py-1.5" style="font-size: 7.5px;">Nama ASN / NIP</th>
                        <th class="px-2 py-1.5" style="font-size: 7.5px;">Kecamatan / Instansi</th>
                        <th class="px-2 py-1.5" style="font-size: 7.5px;">Jenis Layanan</th>
                        <th class="px-2 py-1.5 whitespace-nowrap" style="font-size: 7.5px;">Status Akhir</th>
                        <th class="px-2 py-1.5 whitespace-nowrap" style="font-size: 7.5px;">Operator</th>
                        <th class="px-2 py-1.5 whitespace-nowrap text-right" style="font-size: 7.5px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-100 text-slate-700">
                    @php
                        $role = auth()->user()->role;
                        $prefix = $role === 'viewer' ? 'viewer' : 'admin';
                    @endphp
                    @forelse ($layananList as $layanan)
                        <tr class="hover:bg-sky-50/20 transition">
                            <td class="px-2 py-1.5 font-semibold text-slate-900 font-mono whitespace-nowrap">{{ $layanan->nomor_layanan }}</td>
                            <td class="px-2 py-1.5 text-slate-500 whitespace-nowrap">
                                <div>{{ $layanan->tanggal->format('d M Y') }}</div>
                                <div class="text-slate-400 mt-0.5" style="font-size: 7.5px;">{{ $layanan->jam }}</div>
                            </td>
                            <td class="px-2 py-1.5">
                                <div class="font-bold text-slate-900" style="font-size: 9.5px;">{{ $layanan->nama_asn }}</div>
                                <div class="text-slate-500 font-mono mt-0.5" style="font-size: 8px;">NIP. {{ $layanan->nip }}</div>
                            </td>
                            <td class="px-2 py-1.5">
                                <div class="font-semibold text-slate-800">{{ $layanan->kecamatan ?: '-' }}</div>
                                <div class="text-slate-400 mt-0.5" style="font-size: 8px;">{{ $layanan->nama_sekolah ?: $layanan->unit_kerja ?: '-' }}</div>
                            </td>
                            <td class="px-2 py-1.5">
                                <div class="font-semibold text-slate-800">{{ $layanan->layanan }}</div>
                                <div class="text-slate-400 mt-0.5" style="font-size: 8px;">{{ $layanan->sub_layanan }}</div>
                            </td>
                            <td class="px-2 py-1.5 whitespace-nowrap">
                                <span class="inline-block px-1.5 py-0.5 text-4xs font-bold uppercase tracking-wider 
                                    @if($layanan->hasil_layanan === 'Selesai') bg-emerald-100 text-emerald-800
                                    @elseif(in_array($layanan->hasil_layanan, ['Sebagian', 'Menunggu BKPSDM', 'Menunggu BKN'])) bg-amber-100 text-amber-800
                                    @else bg-rose-100 text-rose-800 @endif" style="font-size: 7.5px;">
                                    {{ $layanan->hasil_layanan }}
                                </span>
                            </td>
                            <td class="px-2 py-1.5 text-slate-500 whitespace-nowrap">{{ $layanan->creator?->name ?: 'System' }}</td>
                            <td class="px-2 py-1.5 whitespace-nowrap text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <!-- Detail (Eye Icon) -->
                                    <a href="{{ route($prefix . '.layanan.show', $layanan->id) }}" class="text-sky-600 hover:text-sky-800 transition" title="Detail Layanan">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                    
                                    <!-- Edit (Pencil Icon - Admin/Operator only) -->
                                    @if($role === 'admin' || $role === 'operator')
                                        <a href="{{ route('admin.layanan.edit', $layanan->id) }}" class="text-amber-600 hover:text-amber-800 transition" title="Edit Layanan">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>
                                    @endif
                                    
                                    <!-- Hapus (Trash Icon - Admin only) -->
                                    @if($role === 'admin')
                                        <form method="POST" action="{{ route('admin.layanan.destroy', $layanan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data layanan ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 transition align-middle" title="Hapus Layanan">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-2 py-4 text-center text-slate-400" style="font-size: 10px;">
                                Belum ada data layanan kepegawaian yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($layananList->hasPages())
            <div class="border-t border-sky-100 px-3 py-1 bg-sky-50/10" style="font-size: 9px;">
                {{ $layananList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
