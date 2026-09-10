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
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@sekolah.id'],
            [
                'name' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'nisn' => null,
            ]
        );

        // Import siswa dari file Excel jika file ada
        if (file_exists(base_path('KELAS 12.xlsx'))) {
            $this->command->call('import:siswa-excel');
        }

        // Seeder Bank Soal RIASEC dan Rekomendasi Karier
        $this->call([
            CareerQuestionSeeder::class,
            CareerRecommendationSeeder::class,
        ]);
    }
}
