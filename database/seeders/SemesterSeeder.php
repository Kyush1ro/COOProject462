<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Fall 2025 (Active)
        Semester::create([
            'id' => 251, // 25 (Year) + 1 (Fall)
            'name' => 'Fall 2025',
            'start_date' => '2025-09-01',
            'end_date' => '2025-12-31',
            'is_active' => true,
        ]);

        // Seed Spring 2026 (Upcoming)
        Semester::create([
            'id' => 262, // 26 (Year) + 2 (Spring)
            'name' => 'Spring 2026',
            'start_date' => '2026-01-15',
            'end_date' => '2026-05-30',
            'is_active' => false,
        ]);
    }
}
