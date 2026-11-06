@extends('layouts.app')

@section('title', 'Master Data - PENA')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="border-b border-sky-100 pb-3">
        <p class="text-4xs font-bold uppercase tracking-wider text-sky-600">Pengaturan Data Master</p>
        <h1 class="mt-1 text-lg font-bold tracking-tight text-slate-900">Kelola Wilayah & Lembaga Sekolah</h1>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_2fr]">
        <!-- 1. KECAMATAN MANAGEMENT -->
        <div class="space-y-4">
            <!-- Add Kecamatan Form -->
            <div class="border border-sky-100 bg-white p-4 shadow-3xs">
                <h3 class="text-xs font-bold text-sky-900 border-b border-sky-50 pb-1.5 mb-3 uppercase tracking-wider">Tambah Kecamatan</h3>
                <form method="POST" action="{{ route('admin.master.kecamatan.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label for="nama_kecamatan" class="block text-4xs font-bold text-slate-700 uppercase mb-0.5">Nama Kecamatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" id="nama_kecamatan" required placeholder="Contoh: Semanding" class="w-full border border-sky-100 px-2.5 py-1.5 text-xs focus:border-sky-500 focus:outline-none">
                    </div>
                    <button type="submit" class="w-full bg-sky-600 text-white py-1.5 text-center text-xs font-semibold hover:bg-sky-700 transition">
                        Simpan Kecamatan
                    </button>
                </form>
            </div>

            <!-- List Kecamatan -->
            <div class="border border-sky-100 bg-white shadow-3xs">
                <div class="border-b border-sky-100 px-4 py-2.5">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Daftar Kecamatan</h3>
                </div>
                <div class="max-h-[300px] overflow-y-auto">
                    <table class="w-full text-left text-2xs">
                        <thead>
                            <tr class="border-b border-sky-100 bg-sky-50/40 text-4xs font-bold uppercase tracking-wider text-sky-900">
                                <th class="px-4 py-2">Kecamatan</th>
                                <th class="px-4 py-2 text-center">Sekolah</th>
                                <th class="px-4 py-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @forelse($kecamatans as $kec)
                                <tr class="hover:bg-sky-50/10">
                                    <td class="px-4 py-2 font-semibold text-slate-800">{{ $kec->nama }}</td>
                                    <td class="px-4 py-2 text-center font-bold text-sky-600">{{ $kec->sekolahs_count }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <form method="POST" action="{{ route('admin.master.kecamatan.destroy', $kec->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kecamatan ini? Sekolah di dalamnya akan terpengaruh.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-3xs font-bold text-rose-600 hover:text-rose-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-slate-400 text-3xs">Belum ada data kecamatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 2. SEKOLAH / SATUAN PENDIDIKAN MANAGEMENT -->
        <div class="space-y-4">
            <!-- Add Sekolah Form -->
            <div class="border border-sky-100 bg-white p-4 shadow-3xs">
                <h3 class="text-xs font-bold text-sky-900 border-b border-sky-50 pb-1.5 mb-3 uppercase tracking-wider">Tambah Lembaga Sekolah</h3>
                <form method="POST" action="{{ route('admin.master.sekolah.store') }}" class="space-y-3">
                    @csrf
                    <div class="grid gap-3 md:grid-cols-3">
                        <!-- Kecamatan Selection -->
                        <div>
                            <label for="kecamatan_id" class="block text-4xs font-bold text-slate-700 uppercase mb-0.5">Kecamatan <span class="text-rose-500">*</span></label>
                            <select name="kecamatan_id" id="kecamatan_id" required class="w-full border border-sky-100 px-2.5 py-1.5 text-xs focus:border-sky-500 focus:outline-none">
                                <option value="">-- Pilih --</option>
                                @foreach($kecamatans as $kec)
                                    <option value="{{ $kec->id }}">{{ $kec->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jenjang -->
                        <div>
                            <label for="jenjang_sekolah" class="block text-4xs font-bold text-slate-700 uppercase mb-0.5">Jenjang Sekolah <span class="text-rose-500">*</span></label>
                            <select name="jenjang" id="jenjang_sekolah" required class="w-full border border-sky-100 px-2.5 py-1.5 text-xs focus:border-sky-500 focus:outline-none">
                                <option value="">-- Pilih --</option>
                                <option value="KB sederajat">KB sederajat</option>
                                <option value="TK sederajat">TK sederajat</option>
                                <option value="SD sederajat">SD sederajat</option>
                                <option value="SMP sederajat">SMP sederajat</option>
                            </select>
                        </div>

                        <!-- Nama Sekolah -->
                        <div>
                            <label for="nama_sekolah" class="block text-4xs font-bold text-slate-700 uppercase mb-0.5">Nama Lembaga <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" id="nama_sekolah" required placeholder="Contoh: SDN Ronggolawe 1" class="w-full border border-sky-100 px-2.5 py-1.5 text-xs focus:border-sky-500 focus:outline-none">
                        </div>
                    </div>
                    <div class="flex justify-end pt-1">
                        <button type="submit" class="bg-sky-600 text-white px-4 py-1.5 text-center text-xs font-semibold hover:bg-sky-700 transition">
                            Simpan Lembaga
                        </button>
                    </div>
                </form>
            </div>

            <!-- List Sekolah -->
            <div class="border border-sky-100 bg-white shadow-3xs">
                <div class="border-b border-sky-100 px-4 py-2.5">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Daftar Lembaga Sekolah</h3>
                </div>
                <div class="max-h-[350px] overflow-y-auto">
                    <table class="w-full text-left text-2xs">
                        <thead>
                            <tr class="border-b border-sky-100 bg-sky-50/40 text-4xs font-bold uppercase tracking-wider text-sky-900">
                                <th class="px-4 py-2">Nama Lembaga</th>
                                <th class="px-4 py-2">Jenjang</th>
                                <th class="px-4 py-2">Kecamatan</th>
                                <th class="px-4 py-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @forelse($sekolahs as $sek)
                                <tr class="hover:bg-sky-50/10">
                                    <td class="px-4 py-2 font-semibold text-slate-800 text-xs">{{ $sek->nama }}</td>
                                    <td class="px-4 py-2 text-slate-500">
                                        <span class="inline-block px-1.5 py-0.5 text-4xs uppercase tracking-wider font-bold bg-slate-100 text-slate-700">
                                            {{ $sek->jenjang }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-slate-500 font-medium">{{ $sek->kecamatan?->nama ?: '-' }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <form method="POST" action="{{ route('admin.master.sekolah.destroy', $sek->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sekolah ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-3xs font-bold text-rose-600 hover:text-rose-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-400 text-3xs">Belum ada data sekolah/lembaga terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
