<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ensure only students can access this
        if (!$user->isStudent()) {
            abort(403, 'Only students can view their grades.');
        }

        // STATE 1: SHOW GRADES FOR A SPECIFIC COURSE
        if ($request->has('course_id')) {
            $course = Course::findOrFail($request->course_id);

            // Authorization: Ensure student is enrolled
            if (!$user->enrolledCourses->contains($course->id)) {
                abort(403, 'You are not enrolled in this course.');
            }

            // Load assignments and this student's specific submission for each
            $course->load(['assignments.submissions' => function($q) use ($user) {
                $q->where('student_id', $user->Academic_ID);
            }]);

            return view('student.grades.index', [
                'selectedCourse' => $course
            ]);
        }

        // STATE 2: LIST ENROLLED COURSES
        // Eager load assignments and submissions to calculate totals efficiently in the view
        $courses = $user->enrolledCourses()->with(['assignments.submissions' => function($q) use ($user) {
            $q->where('student_id', $user->Academic_ID);
        }])->get();

        return view('student.grades.index', compact('courses'));
    }
}
