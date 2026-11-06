<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('password_changed_at')->nullable()->after('password');
            $table->boolean('must_change_password')->default(false)->after('password_changed_at');
            $table->index('role');
        });

        Schema::table('kecamatans', function (Blueprint $table) {
            $table->unique('nama');
        });

        Schema::table('sekolahs', function (Blueprint $table) {
            $table->unique(['kecamatan_id', 'nama']);
            $table->index('jenjang');
        });

        Schema::table('layanan_kepegawaian', function (Blueprint $table) {
            $table->foreignId('kecamatan_id')
                ->nullable()
                ->after('email')
                ->constrained('kecamatans')
                ->nullOnDelete();
            $table->foreignId('sekolah_id')
                ->nullable()
                ->after('kecamatan_id')
                ->constrained('sekolahs')
                ->nullOnDelete();
            $table->string('unit_kerja', 500)->nullable()->change();
            $table->index('nip');
            $table->index('tanggal');
            $table->index(['tanggal', 'hasil_layanan']);
            $table->index(['layanan', 'tanggal']);
        });

        Schema::create('service_number_sequences', function (Blueprint $table) {
            $table->date('service_date')->primary();
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();
        });

        DB::table('layanan_kepegawaian')
            ->whereNotNull('kecamatan')
            ->orderBy('id')
            ->each(function (object $record): void {
                $kecamatanId = DB::table('kecamatans')->where('nama', $record->kecamatan)->value('id');
                $sekolahId = null;

                if ($kecamatanId && $record->nama_sekolah) {
                    $sekolahId = DB::table('sekolahs')
                        ->where('kecamatan_id', $kecamatanId)
                        ->where('nama', $record->nama_sekolah)
                        ->value('id');
                }

                DB::table('layanan_kepegawaian')->where('id', $record->id)->update([
                    'kecamatan_id' => $kecamatanId,
                    'sekolah_id' => $sekolahId,
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_number_sequences');

        Schema::table('layanan_kepegawaian', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
            $table->dropForeign(['kecamatan_id']);
            $table->dropIndex(['nip']);
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['tanggal', 'hasil_layanan']);
            $table->dropIndex(['layanan', 'tanggal']);
            $table->dropColumn(['sekolah_id', 'kecamatan_id']);
            $table->string('unit_kerja')->nullable()->change();
        });

        Schema::table('sekolahs', function (Blueprint $table) {
            $table->dropUnique(['kecamatan_id', 'nama']);
            $table->dropIndex(['jenjang']);
        });

        Schema::table('kecamatans', function (Blueprint $table) {
            $table->dropUnique(['nama']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn(['password_changed_at', 'must_change_password']);
        });
    }
};