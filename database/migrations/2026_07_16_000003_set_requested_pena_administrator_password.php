<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('email', 'asdmaparatur@gmail.com')
            ->update([
                'password' => Hash::make('Asdmaparatur123!'),
                'password_changed_at' => now(),
                'must_change_password' => false,
                'role' => 'admin',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Password aktif tidak di-rollback demi mencegah kredensial lama hidup kembali.
    }
};