<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('layanan_kepegawaian', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_layanan')->unique(); // e.g. CSV-20260707-0001
            $table->date('tanggal');
            $table->time('jam');
            $table->string('nama_asn');
            $table->string('nip');
            $table->string('nik')->nullable();
            $table->string('no_hp');
            $table->string('email');
            
            // Asal Instansi
            $table->string('kecamatan')->nullable(); // Dropdown/text
            $table->string('jenjang')->nullable(); // KB/TK/SD/SMP/Umum
            $table->string('nama_sekolah')->nullable();
            $table->string('unit_kerja')->nullable(); // Alamat jika masyarakat umum
            
            // Layanan
            $table->string('layanan'); // SIASN, SITEMAN, SIJEMPOL, Lainnya
            $table->string('sub_layanan'); // Reset Email, dll.
            $table->text('permasalahan');
            $table->text('tindak_lanjut')->nullable();
            $table->string('hasil_layanan'); // Selesai, Sebagian, Menunggu BKPSDM, Menunggu BKN, Ditolak, Pending
            
            // Auditor
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_kepegawaian');
    }
};
