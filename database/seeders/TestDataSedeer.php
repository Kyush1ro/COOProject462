<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Department;
use App\Models\Semester;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestDataSedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'Academic_ID' => '1000', // IMPORTANT: Use your custom key
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $instructor = User::create([
            'Academic_ID' => '2000',
            'name' => 'Professor Smith',
            'email' => 'instructor@test.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
        ]);

        $student = User::create([
            'Academic_ID' => '3000',
            'name' => 'Student Alex',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        // 2. Create Departments (NEW)
        $deptCS = Department::create([
            'name' => 'Computer Science',
            'code' => 'CS',

        ]);

        $deptIT = Department::create([
            'name' => 'Information Technology',
            'code' => 'IT',

        ]);


        // 3. Create Semesters (Moved before courses)
        // Seed Fall 2025 (Active)
        $fall2025 = Semester::create([
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

        // 3. Create Courses (Update to include department_id)
        // 4. Create Courses (Update to include department_id and semester_id)
        $course1 = Course::create([
            'title' => 'Advanced Laravel',
            'course_code' => 'CS501',
            'instructor_id' => $instructor->Academic_ID,
            'classroom' => 'Room 304',
            'course_type' => 'theory',
            'department_id' => $deptCS->id, // <--- Linked!
            'semester_id' => $fall2025->id, // Fall 2025

            'department_id' => $deptCS->id,
            'semester_id' => $fall2025->id, // <--- Linked!
        ]);

        $course2 = Course::create([
            'title' => 'Web Security',
            'course_code' => 'CS502',
            'instructor_id' => $instructor->Academic_ID,
            'classroom' => 'Lab A',
            'course_type' => 'lab',
            'department_id' => $deptIT->id, // <--- Linked!
            'semester_id' => $fall2025->id, // Fall 2025

        ]);
        // 3. Enroll the Student in Course 1 (using the pivot table)
        Enrollment::create([
            'student_id' => $student->Academic_ID,
            'course_id' => $course1->id,
        ]);

        // 4. Seed Classrooms

        $classrooms = [
            'Room 101',
            'Room 102',
            'Room 103',
            'Lab A',
            'Lab B',
            'Lab C',
            'Lecture Hall 1',
            'Lecture Hall 2',
            'Auditorium',
            'Online',
            'Room 101',
            'Room 102',
            'Room 103',
            'Lab A',
            'Lab B',
            'Lab C',
            'Lecture Hall 1',
            'Lecture Hall 2',
            'Auditorium',
            'Online'
        ];

        foreach ($classrooms as $name) {
            \App\Models\Classroom::firstOrCreate(['name' => $name]);
        }
    }
}
