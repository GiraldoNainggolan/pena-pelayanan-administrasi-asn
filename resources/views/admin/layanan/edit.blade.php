@extends('layouts.app')

@section('title', 'Edit Layanan - PENA')

@section('content')
<div class="mx-auto w-full max-w-6xl space-y-5">
    <!-- Header -->
    <x-dashboard-header 
        title="Edit Data Layanan #{{ $layanan->nomor_layanan }}" 
        subtitle="Form Asistensi Kepegawaian" 
    />

    <!-- Form -->
    <form method="POST" action="{{ route('admin.layanan.update', $layanan->id) }}" class="rounded-xl border border-slate-100 bg-white shadow-2xs overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="grid lg:grid-cols-12 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
            
            <!-- Kolom Kiri: Data ASN & Instansi -->
            <div class="lg:col-span-5 p-5 sm:p-6 bg-slate-50/30">
                <!-- Identitas ASN -->
                <h3 class="text-[11px] font-extrabold text-sky-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    Identitas Pegawai
                </h3>
                <div class="grid gap-3.5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="nama_asn" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_asn" id="nama_asn" value="{{ old('nama_asn', $layanan->nama_asn) }}" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                    </div>
                    <div>
                        <label for="nip" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">NIP <span class="text-rose-500">*</span></label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip', $layanan->nip) }}" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-mono focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                    </div>
                    <div>
                        <label for="nik" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">NIK (Nomor KTP)</label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik', $layanan->nik) }}" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-mono focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                    </div>
                    <div>
                        <label for="no_hp" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $layanan->no_hp) }}" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-mono focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                    </div>
                    <div>
                        <label for="email" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $layanan->email) }}" required class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                    </div>
                </div>

                <div class="my-5 border-t border-slate-100/80"></div>

                <!-- Instansi -->
                <h3 class="text-[11px] font-extrabold text-sky-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                    Asal Instansi
                </h3>
                <div class="grid gap-3.5 sm:grid-cols-2">
                    <div>
                        <label for="kecamatan_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Kecamatan</label>
                        <select name="kecamatan_id" id="kecamatan_id" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                            <option value="">-- Kecamatan --</option>
                            @foreach($kecamatans as $kec)
                                <option value="{{ $kec->id }}" {{ (string) old('kecamatan_id', $layanan->kecamatan_id) === (string) $kec->id ? 'selected' : '' }}>{{ $kec->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="jenjang" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jenjang</label>
                        <select name="jenjang" id="jenjang" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                            <option value="">-- Jenjang --</option>
                            <option value="KB sederajat" {{ old('jenjang', $layanan->jenjang) === 'KB sederajat' ? 'selected' : '' }}>KB</option>
                            <option value="TK sederajat" {{ old('jenjang', $layanan->jenjang) === 'TK sederajat' ? 'selected' : '' }}>TK</option>
                            <option value="SD sederajat" {{ old('jenjang', $layanan->jenjang) === 'SD sederajat' ? 'selected' : '' }}>SD</option>
                            <option value="SMP sederajat" {{ old('jenjang', $layanan->jenjang) === 'SMP sederajat' ? 'selected' : '' }}>SMP</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="sekolah_id" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Sekolah</label>
                        <select name="sekolah_id" id="sekolah_id" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                            <option value="">-- Pilih Sekolah --</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="unit_kerja" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Atau Unit Kerja Lain</label>
                        <input type="text" name="unit_kerja" id="unit_kerja" value="{{ old('unit_kerja', $layanan->unit_kerja) }}" placeholder="Dinas Pendidikan, dll." class="w-full rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Detail Layanan -->
            <div class="lg:col-span-7 p-5 sm:p-6 bg-white">
                <h3 class="text-[11px] font-extrabold text-sky-800 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Detail Konsultasi & Hasil
                </h3>
                
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="layanan" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Layanan <span class="text-rose-500">*</span></label>
                        <select name="layanan" id="layanan" required class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-1.5 text-xs font-semibold text-sky-900 focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                            <option value="">-- Pilih Jenis Layanan --</option>
                            <option value="Layanan SIASN" {{ old('layanan', $layanan->layanan) === 'Layanan SIASN' ? 'selected' : '' }}>Layanan SIASN</option>
                            <option value="Layanan SIJEMPOL" {{ old('layanan', $layanan->layanan) === 'Layanan SIJEMPOL' ? 'selected' : '' }}>Layanan SIJEMPOL</option>
                            <option value="Layanan SITEMAN" {{ old('layanan', $layanan->layanan) === 'Layanan SITEMAN' ? 'selected' : '' }}>Layanan SITEMAN</option>
                            <option value="Kenaikan Pangkat" {{ old('layanan', $layanan->layanan) === 'Kenaikan Pangkat' ? 'selected' : '' }}>Kenaikan Pangkat</option>
                            <option value="Pensiun" {{ old('layanan', $layanan->layanan) === 'Pensiun' ? 'selected' : '' }}>Pensiun</option>
                            <option value="Kenaikan Gaji Berkala" {{ old('layanan', $layanan->layanan) === 'Kenaikan Gaji Berkala' ? 'selected' : '' }}>Kenaikan Gaji Berkala</option>
                            <option value="Mutasi / Promosi" {{ old('layanan', $layanan->layanan) === 'Mutasi / Promosi' ? 'selected' : '' }}>Mutasi / Promosi</option>
                            <option value="Diklat / Tugas Belajar" {{ old('layanan', $layanan->layanan) === 'Diklat / Tugas Belajar' ? 'selected' : '' }}>Diklat / Tugas Belajar</option>
                            <option value="Disiplin & Kesejahteraan" {{ old('layanan', $layanan->layanan) === 'Disiplin & Kesejahteraan' ? 'selected' : '' }}>Disiplin & Kesejahteraan</option>
                            <option value="Lainnya" {{ old('layanan', $layanan->layanan) === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label for="sub_layanan" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Sub Layanan <span class="text-rose-500">*</span></label>
                        <select name="sub_layanan" id="sub_layanan" required class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-1.5 text-xs focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                            <option value="">-- Pilih Sub Layanan --</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="permasalahan" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Deskripsi Permasalahan <span class="text-rose-500">*</span></label>
                        <textarea name="permasalahan" id="permasalahan" rows="3" required placeholder="Detail permasalahan..." class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs leading-relaxed focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">{{ old('permasalahan', $layanan->permasalahan) }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="tindak_lanjut" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tindak Lanjut / Solusi</label>
                        <textarea name="tindak_lanjut" id="tindak_lanjut" rows="2" placeholder="Langkah yang telah dilakukan..." class="w-full rounded-lg border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs leading-relaxed focus:bg-white focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">{{ old('tindak_lanjut', $layanan->tindak_lanjut) }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="hasil_layanan" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Status Hasil Akhir <span class="text-rose-500">*</span></label>
                        <select name="hasil_layanan" id="hasil_layanan" required class="w-full sm:w-1/2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-800 focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition">
                            <option value="Selesai" {{ old('hasil_layanan', $layanan->hasil_layanan) === 'Selesai' ? 'selected' : '' }}>✓ Selesai (Tuntas)</option>
                            <option value="Sebagian" {{ old('hasil_layanan', $layanan->hasil_layanan) === 'Sebagian' ? 'selected' : '' }}>⏱ Selesai Sebagian</option>
                            <option value="Menunggu BKPSDM" {{ old('hasil_layanan', $layanan->hasil_layanan) === 'Menunggu BKPSDM' ? 'selected' : '' }}>⏳ Menunggu BKPSDM</option>
                            <option value="Menunggu BKN" {{ old('hasil_layanan', $layanan->hasil_layanan) === 'Menunggu BKN' ? 'selected' : '' }}>⏳ Menunggu BKN</option>
                            <option value="Pending" {{ old('hasil_layanan', $layanan->hasil_layanan) === 'Pending' ? 'selected' : '' }}>⚠ Pending (Kurang Syarat)</option>
                            <option value="Ditolak" {{ old('hasil_layanan', $layanan->hasil_layanan) === 'Ditolak' ? 'selected' : '' }}>✕ Ditolak</option>
                        </select>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Tombol Aksi di Bawah -->
        <div class="flex items-center justify-end gap-3 p-4 bg-slate-50/80 border-t border-slate-100">
            <a href="{{ route('admin.layanan.index') }}" class="rounded-lg bg-white border border-slate-200 text-slate-600 px-5 py-2 text-xs font-bold hover:bg-slate-100 transition shadow-xs">
                Batal
            </a>
            <button type="submit" class="rounded-lg bg-sky-600 border border-sky-600 text-white px-6 py-2 text-xs font-bold hover:bg-sky-700 transition shadow-xs flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<!-- Cascading Dropdown Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const kecamatans = {!! json_encode($kecamatans) !!};
        const kecamatanSelect = document.getElementById('kecamatan_id');
        const jenjangSelect = document.getElementById('jenjang');
        const sekolahSelect = document.getElementById('sekolah_id');

        const subLayananMap = {
            'Layanan SIASN': ['Reset Password SIASN', 'Koordinasi terkait SKP', 'Reset MFA SIASN', 'Koordinasi terkait Ekinerja', 'Koordinasi terkait MyASN', 'Reset Email SIASN', 'Lainnya'],
            'Layanan SIJEMPOL': ['Tata cara unggah dokumen cuti / Dispensasi', 'Lainnya'],
            'Layanan SITEMAN': ['Lainnya'],
            'Kenaikan Pangkat': ['KP Reguler', 'KP Pilihan (Jabatan Fungsional)', 'KP Pilihan (Struktural)', 'KP Penyesuaian Ijazah', 'Lainnya'],
            'Pensiun': ['Pensiun BUP (Batas Usia Pensiun)', 'Pensiun Janda/Duda', 'Pensiun Atas Permintaan Sendiri (APS)', 'Pensiun Uzur/Sakit', 'Lainnya'],
            'Kenaikan Gaji Berkala': ['KGB Rutin', 'KGB Susulan', 'KGB Koreksi', 'Lainnya'],
            'Mutasi / Promosi': ['Mutasi Masuk Kabupaten', 'Mutasi Keluar Kabupaten', 'Mutasi Antar Instansi Internal', 'Promosi Jabatan', 'Lainnya'],
            'Diklat / Tugas Belajar': ['Tugas Belajar', 'Izin Belajar', 'Pencantuman Gelar', 'Ujian Dinas', 'Diklat Pim/Teknis', 'Lainnya'],
            'Disiplin & Kesejahteraan': ['Cuti Pegawai', 'Hukuman Disiplin', 'Pemberhentian Sementara', 'BPJS/Taspen', 'Satya Lencana', 'Lainnya'],
            'Lainnya': ['Konsultasi Umum', 'Pemutakhiran Data Mandiri (PDM)', 'Karis/Karsu/Taspen', 'Lainnya']
        };

        const layananSelect = document.getElementById('layanan');
        const subLayananSelect = document.getElementById('sub_layanan');

        const selectedSekolahVal = @json((string) old('sekolah_id', $layanan->sekolah_id));
        const selectedSubLayananVal = @json(old('sub_layanan', $layanan->sub_layanan));

        function updateSekolahOptions() {
            const selectedKecId = kecamatanSelect.value;
            const selectedJenjang = jenjangSelect.value;
            sekolahSelect.innerHTML = '<option value="">-- Pilih Sekolah --</option>';

            if (!selectedKecId) return;

            const matchingKec = kecamatans.find(k => String(k.id) === selectedKecId);
            if (!matchingKec || !matchingKec.sekolahs) return;

            let filteredSekolahs = matchingKec.sekolahs;
            if (selectedJenjang) {
                filteredSekolahs = filteredSekolahs.filter(s => s.jenjang === selectedJenjang);
            }

            filteredSekolahs.forEach(sekolah => {
                const opt = document.createElement('option');
                opt.value = sekolah.id;
                opt.textContent = sekolah.nama + ' (' + sekolah.jenjang + ')';
                if (String(sekolah.id) === selectedSekolahVal) opt.selected = true;
                sekolahSelect.appendChild(opt);
            });
        }

        kecamatanSelect.addEventListener('change', updateSekolahOptions);
        jenjangSelect.addEventListener('change', updateSekolahOptions);

        function updateSubLayananOptions() {
            const selectedLayanan = layananSelect.value;
            subLayananSelect.innerHTML = '<option value="">-- Pilih Sub Layanan --</option>';

            if (!selectedLayanan || !subLayananMap[selectedLayanan]) return;

            const options = subLayananMap[selectedLayanan];
            options.forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub;
                opt.textContent = sub;
                if (sub === selectedSubLayananVal) opt.selected = true;
                subLayananSelect.appendChild(opt);
            });
        }

        layananSelect.addEventListener('change', updateSubLayananOptions);

        updateSekolahOptions();
        updateSubLayananOptions();
    });
</script>
@endsection
