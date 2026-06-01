<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;


class StudentListController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $instructorr */
        $instructorr = Auth::user();

        // Ensure user is an instructor


        if (!$instructorr->isInstructor()) {
            abort(403, 'Only instructors can view this page.');
        }

        // Get all courses taught by this instructor
        $courses = Course::where('instructor_id', $instructorr->Academic_ID)->get();

        $selectedCourse = null;
        $students = collect();

        // If a course is selected, get its students
        if ($request->has('course_id')) {
            $selectedCourse = $courses->firstWhere('id', $request->course_id);

            if ($selectedCourse) {
                $students = $selectedCourse->students;
            }
        }

        return view('instructor.students.index', compact('courses', 'selectedCourse', 'students'));
    }
}
