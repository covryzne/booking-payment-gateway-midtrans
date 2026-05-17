<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin GOR
        User::create([
            'name' => 'Admin GOR Rajawali',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Sesuaikan dengan string enum di migration add_role lu (misal: 'admin' atau 1)
        ]);

        // 2. Akun Petugas Lapangan
        User::create([
            'name' => 'Petugas Lapangan',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'petugas', // Sesuaikan dengan string enum di migration
        ]);

        // 3. Akun Penyewa / Customer Dummy
        User::create([
            'name' => 'Shendi Penyewa',
            'email' => 'penyewa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'penyewa', // Sesuaikan dengan string enum di migration
        ]);

        // Tambahan: Bikin 5 user penyewa random pakai factory buat ngeramein database
        User::factory(5)->create([
            'role' => 'penyewa'
        ]);
    }
}
