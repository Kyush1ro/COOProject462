<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classrooms = [
            'Room 101', 'Room 102', 'Room 103',
            'Lab A', 'Lab B', 'Lab C',
            'Lecture Hall 1', 'Lecture Hall 2',
            'Auditorium', 'Online'
        ];

        foreach ($classrooms as $name) {
            \App\Models\Classroom::firstOrCreate(['name' => $name]);
        }
    }
}
