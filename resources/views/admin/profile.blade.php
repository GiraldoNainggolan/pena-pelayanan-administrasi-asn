@extends('layouts.app')

@section('title', 'Profil Pengguna - PENA')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="border-b border-sky-100 pb-6">
        <p class="text-sm font-semibold uppercase tracking-wider text-sky-600">Informasi Pengguna</p>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Profil Saya</h1>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <!-- Card Foto/Identity -->
        <div class="border border-sky-100 bg-white p-6 shadow-2xs text-center flex flex-col items-center justify-center">
            <div class="h-20 w-20 bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-3xl mb-4 border border-sky-100">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <h2 class="text-base font-bold text-slate-950">{{ auth()->user()->name }}</h2>
            <p class="text-xs text-slate-500 mt-1">{{ auth()->user()->email }}</p>
            <span class="inline-block mt-3 px-3 py-1 text-3xs font-bold uppercase tracking-wider bg-sky-100 text-sky-800">
                {{ auth()->user()->role }}
            </span>
        </div>

        <!-- Detail Akun & Hak Akses -->
        <div class="md:col-span-2 space-y-6">
            <!-- Detail Akun -->
            <div class="border border-sky-100 bg-white p-6 shadow-2xs">
                <h3 class="text-xs font-bold text-sky-900 border-b border-sky-50 pb-2 mb-4 uppercase tracking-wider">Detail Informasi Akun</h3>
                <div class="grid gap-4 sm:grid-cols-2 text-sm">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Nama Lengkap</p>
                        <p class="mt-1 font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Alamat Email</p>
                        <p class="mt-1 text-slate-800">{{ auth()->user()->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Peran / Hak Akses</p>
                        <p class="mt-1 font-semibold text-sky-700 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">Status Akun</p>
                        <p class="mt-1 text-emerald-600 font-semibold flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                            Aktif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Kewenangan Akses -->
            <div class="border border-sky-100 bg-white p-6 shadow-2xs">
                <h3 class="text-xs font-bold text-sky-900 border-b border-sky-50 pb-2 mb-4 uppercase tracking-wider">Kewenangan Akses Peran (Role Privileges)</h3>
                <div class="space-y-3.5 text-xs">
                    <div>
                        <span class="font-semibold text-slate-700 block">Kategori Hak Akses Anda:</span>
                        @if(auth()->user()->role === 'admin')
                            <p class="text-slate-500 mt-1">Anda masuk sebagai <strong>Administrator</strong>. Anda memiliki wewenang penuh di dalam sistem, termasuk:</p>
                            <ul class="mt-2 list-disc list-inside space-y-1 text-slate-600 pl-2">
                                <li>Melihat Dashboard statistik & tren rekapitulasi kepegawaian</li>
                                <li>Melakukan Input, Edit, dan Hapus data konsultasi layanan</li>
                                <li>Melakukan ekspor laporan data ke format Excel dan cetak PDF</li>
                                <li>Mengelola data master wilayah kecamatan dan daftar lembaga sekolah</li>
                                <li>Mengelola akun pengguna (tambah, edit, hapus operator/viewer)</li>
                            </ul>
                        @elseif(auth()->user()->role === 'operator')
                            <p class="text-slate-500 mt-1">Anda masuk sebagai <strong>Operator / Pelaksana</strong>. Anda memiliki wewenang operasional untuk pelayanan:</p>
                            <ul class="mt-2 list-disc list-inside space-y-1 text-slate-600 pl-2">
                                <li>Melihat Dashboard statistik & tren rekapitulasi kepegawaian</li>
                                <li>Melakukan Input dan Edit data konsultasi layanan</li>
                                <li>Melakukan ekspor laporan data ke format Excel dan cetak PDF</li>
                                <li>Melihat data master wilayah kecamatan dan daftar lembaga sekolah</li>
                            </ul>
                        @else
                            <p class="text-slate-500 mt-1">Anda masuk sebagai <strong>Viewer / Pemimpin (Kabid/Kadin)</strong>. Anda memiliki akses pemantauan (Read-Only):</p>
                            <ul class="mt-2 list-disc list-inside space-y-1 text-slate-600 pl-2">
                                <li>Melihat Dashboard statistik & tren rekapitulasi kepegawaian</li>
                                <li>Melihat data riwayat konsultasi layanan kepegawaian</li>
                                <li>Melakukan cetak PDF kartu kendali asistensi kepegawaian</li>
                                <li>Melihat analisis laporan grafis & rekap bulanan/tahunan</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Ubah Password -->
    <div class="border border-sky-100 bg-white p-6 shadow-2xs">
        <h3 class="text-xs font-bold text-sky-900 border-b border-sky-50 pb-2 mb-4 uppercase tracking-wider">Ubah Kata Sandi (Password)</h3>

        <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4 max-w-md">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Password Saat Ini <span class="text-rose-500">*</span></label>
                <input type="password" name="current_password" id="current_password" required autocomplete="current-password" class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Password Baru <span class="text-rose-500">*</span></label>
                <input type="password" name="password" id="password" required minlength="12" autocomplete="new-password" class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
                <p class="text-3xs text-slate-400 mt-1">Minimal 12 karakter, kombinasi huruf besar, kecil, angka & simbol.</p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Konfirmasi Password Baru <span class="text-rose-500">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" required minlength="12" autocomplete="new-password" class="w-full border border-sky-100 px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-sky-600 text-white px-6 py-2 text-sm font-semibold hover:bg-sky-700 transition">
                    Simpan Password Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
