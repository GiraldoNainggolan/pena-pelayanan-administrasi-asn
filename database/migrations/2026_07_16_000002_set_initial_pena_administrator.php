<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $targetEmail = 'asdmaparatur@gmail.com';
        $existingTarget = DB::table('users')->where('email', $targetEmail)->first();
        $legacyAdmin = DB::table('users')->where('email', 'admin@example.com')->first();

        if ($existingTarget) {
            DB::table('users')->where('id', $existingTarget->id)->update([
                'name' => 'Administrator PENA',
                'password' => Hash::make('admin123'),
                'password_changed_at' => now(),
                'must_change_password' => true,
                'role' => 'admin',
                'updated_at' => now(),
            ]);

            return;
        }

        if ($legacyAdmin) {
            DB::table('users')->where('id', $legacyAdmin->id)->update([
                'name' => 'Administrator PENA',
                'email' => $targetEmail,
                'password' => Hash::make('admin123'),
                'password_changed_at' => now(),
                'must_change_password' => true,
                'role' => 'admin',
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Kredensial tidak di-rollback agar password aktif tidak ditimpa.
    }
};