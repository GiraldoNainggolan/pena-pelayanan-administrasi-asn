<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Membuat administrator awal tanpa mereset password saat seed diulang. */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'asdmaparatur@gmail.com'],
            [
                'name' => 'Administrator PENA',
                'password' => 'Asdmaparatur123!',
                'password_changed_at' => now(),
                'must_change_password' => false,
                'role' => 'admin',
            ]
        );

        $this->call(LayananExcelSeeder::class);
    }
}
