<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LayananKepegawaian extends Model
{
    use HasFactory;

    public const JENJANG = [
        'KB sederajat',
        'TK sederajat',
        'SD sederajat',
        'SMP sederajat',
    ];

    public const HASIL_OPTIONS = [
        'Selesai',
        'Sebagian',
        'Menunggu BKPSDM',
        'Menunggu BKN',
        'Pending',
        'Ditolak',
    ];

    public const LAYANAN_OPTIONS = [
        'Layanan SIASN' => ['Reset Password SIASN', 'Koordinasi terkait SKP', 'Reset MFA SIASN', 'Koordinasi terkait Ekinerja', 'Koordinasi terkait MyASN', 'Reset Email SIASN', 'Lainnya'],
        'Layanan SIJEMPOL' => ['Tata cara unggah dokumen cuti / Dispensasi', 'Lainnya'],
        'Layanan SITEMAN' => ['Lainnya'],
        'Kenaikan Pangkat' => ['KP Reguler', 'KP Pilihan (Jabatan Fungsional)', 'KP Pilihan (Struktural)', 'KP Penyesuaian Ijazah', 'Lainnya'],
        'Pensiun' => ['Pensiun BUP (Batas Usia Pensiun)', 'Pensiun Janda/Duda', 'Pensiun Atas Permintaan Sendiri (APS)', 'Pensiun Uzur/Sakit', 'Lainnya'],
        'Kenaikan Gaji Berkala' => ['KGB Rutin', 'KGB Susulan', 'KGB Koreksi', 'Lainnya'],
        'Mutasi / Promosi' => ['Mutasi Masuk Kabupaten', 'Mutasi Keluar Kabupaten', 'Mutasi Antar Instansi Internal', 'Promosi Jabatan', 'Lainnya'],
        'Diklat / Tugas Belajar' => ['Tugas Belajar', 'Izin Belajar', 'Pencantuman Gelar', 'Ujian Dinas', 'Diklat Pim/Teknis', 'Lainnya'],
        'Disiplin & Kesejahteraan' => ['Cuti Pegawai', 'Hukuman Disiplin', 'Pemberhentian Sementara', 'BPJS/Taspen', 'Satya Lencana', 'Lainnya'],
        'Lainnya' => ['Konsultasi Umum', 'Pemutakhiran Data Mandiri (PDM)', 'Karis/Karsu/Taspen', 'Lainnya'],
    ];

    protected $table = 'layanan_kepegawaian';

    protected $fillable = [
        'nomor_layanan',
        'tanggal',
        'jam',
        'nama_asn',
        'nip',
        'nik',
        'no_hp',
        'email',
        'kecamatan_id',
        'sekolah_id',
        'kecamatan',
        'jenjang',
        'nama_sekolah',
        'unit_kerja',
        'layanan',
        'sub_layanan',
        'permasalahan',
        'tindak_lanjut',
        'hasil_layanan',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function kecamatanMaster(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function sekolahMaster(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
