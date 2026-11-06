@extends('layouts.app')

@section('title', 'Detail Layanan - PENA')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    @php
        $role = auth()->user()->role;
        $prefix = $role === 'viewer' ? 'viewer' : 'admin';
    @endphp

    <!-- Header / Navigation -->
    <div class="flex flex-col justify-between gap-4 border-b border-sky-100 pb-6 md:flex-row md:items-end">
        <div>
            <a href="{{ route($prefix . '.layanan.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1 mb-2">
                ← Kembali ke Daftar Layanan
            </a>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Detail Layanan #{{ $layanan->nomor_layanan }}</h1>
            <p class="text-xs text-slate-500 mt-1">
                Dibuat oleh <span class="font-semibold text-slate-700">{{ $layanan->creator?->name ?: 'System' }}</span> pada {{ $layanan->tanggal->format('d M Y') }} - {{ $layanan->jam }}
            </p>
        </div>
        <div class="flex flex-wrap gap-2.5">
            <!-- Cetak PDF / Print -->
            <a href="{{ route($prefix . '.print', $layanan->id) }}" target="_blank" class="bg-white border border-sky-200 text-sky-700 px-5 py-2.5 text-center text-sm font-semibold hover:bg-sky-50 transition duration-150 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-14.326 0C3.768 7.28 3 8.215 3 9.296v6.299a2.25 2.25 0 0 0 2.25 2.25h1.091M9 10.125h6M9 13.125h6M9 16.125h3" />
                </svg>
                Cetak Dokumen
            </a>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                <a href="{{ route('admin.layanan.edit', $layanan->id) }}" class="bg-amber-600 text-white px-5 py-2.5 text-center text-sm font-semibold hover:bg-amber-700 transition duration-150">
                    Edit Data
                </a>
            @endif
            @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('admin.layanan.destroy', $layanan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data layanan ini?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-rose-600 text-white px-5 py-2.5 text-center text-sm font-semibold hover:bg-rose-700 transition duration-150">
                        Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Detail Content Grid -->
    <div class="grid gap-6 md:grid-cols-3">
        <!-- Main Details (Left/Center) -->
        <div class="md:col-span-2 space-y-6">
            <!-- Deskripsi Permasalahan -->
            <div class="border border-sky-100 bg-white p-6 shadow-2xs">
                <h3 class="text-xs font-bold text-sky-900 border-b border-sky-50 pb-2 mb-4 uppercase tracking-wider">Deskripsi Masalah / Konsultasi</h3>
                <div class="prose max-w-none text-slate-800 text-sm leading-relaxed whitespace-pre-line">
                    {{ $layanan->permasalahan }}
                </div>
            </div>

            <!-- Tindak Lanjut / Solusi -->
            <div class="border border-sky-100 bg-white p-6 shadow-2xs">
                <h3 class="text-xs font-bold text-sky-900 border-b border-sky-50 pb-2 mb-4 uppercase tracking-wider">Tindak Lanjut / Solusi Yang Diberikan</h3>
                <div class="prose max-w-none text-slate-800 text-sm leading-relaxed whitespace-pre-line">
                    @if($layanan->tindak_lanjut)
                        {{ $layanan->tindak_lanjut }}
                    @else
                        <span class="text-slate-400 italic">Belum ada tindak lanjut yang dimasukkan.</span>
                    @endif
                </div>
            </div>

            <!-- Identitas Lengkap ASN -->
            <div class="border border-sky-100 bg-white p-6 shadow-2xs">
                <h3 class="text-xs font-bold text-sky-900 border-b border-sky-50 pb-2 mb-4 uppercase tracking-wider">Informasi Lengkap ASN</h3>
                <div class="grid gap-4 sm:grid-cols-2 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Nama Lengkap</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $layanan->nama_asn }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">NIP Pegawai</p>
                        <p class="mt-1 font-mono text-slate-800">{{ $layanan->nip }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">NIK (KTP)</p>
                        <p class="mt-1 font-mono text-slate-800">{{ $layanan->nik ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">No. Handphone / WhatsApp</p>
                        <p class="mt-1 text-slate-800">{{ $layanan->no_hp }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase">Alamat Email</p>
                        <p class="mt-1 text-slate-800">{{ $layanan->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Details (Right) -->
        <div class="space-y-6">
            <!-- Status & Kategori -->
            <div class="border border-sky-100 bg-white p-6 shadow-2xs">
                <h3 class="text-xs font-bold text-sky-900 border-b border-sky-50 pb-2 mb-4 uppercase tracking-wider">Status & Klasifikasi</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-3xs font-bold text-slate-400 uppercase tracking-wider">Hasil Layanan</p>
                        <span class="inline-block mt-2 px-3 py-1.5 text-xs font-bold uppercase tracking-wider 
                            @if($layanan->hasil_layanan === 'Selesai') bg-emerald-100 text-emerald-800
                            @elseif(in_array($layanan->hasil_layanan, ['Sebagian', 'Menunggu BKPSDM', 'Menunggu BKN'])) bg-amber-100 text-amber-800
                            @else bg-rose-100 text-rose-800 @endif">
                            {{ $layanan->hasil_layanan }}
                        </span>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-3xs font-bold text-slate-400 uppercase tracking-wider">Kategori Layanan</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $layanan->layanan }}</p>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-3xs font-bold text-slate-400 uppercase tracking-wider">Sub Kategori</p>
                        <p class="mt-1 text-slate-700">{{ $layanan->sub_layanan }}</p>
                    </div>
                </div>
            </div>

            <!-- Instansi/Sekolah Asal -->
            <div class="border border-sky-100 bg-white p-6 shadow-2xs">
                <h3 class="text-xs font-bold text-sky-900 border-b border-sky-50 pb-2 mb-4 uppercase tracking-wider">Unit Kerja Asal</h3>
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-3xs font-bold text-slate-400 uppercase tracking-wider">Kecamatan Wilayah</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ $layanan->kecamatan ?: '-' }}</p>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-3xs font-bold text-slate-400 uppercase tracking-wider">Jenjang Pendidikan</p>
                        <p class="mt-1 text-slate-700">{{ $layanan->jenjang ?: '-' }}</p>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-3xs font-bold text-slate-400 uppercase tracking-wider">Nama Sekolah / Unit Kerja</p>
                        <p class="mt-1 font-medium text-slate-800">
                            @if($layanan->nama_sekolah)
                                {{ $layanan->nama_sekolah }}
                            @else
                                {{ $layanan->unit_kerja ?: '-' }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
