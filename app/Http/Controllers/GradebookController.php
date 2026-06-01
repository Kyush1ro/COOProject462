<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradebookController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security: Only Instructors (and maybe Admin) can view the Gradebook
        if (!$user->isInstructor() && !$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        // STATE 1: SHOW GRADEBOOK FOR A SPECIFIC COURSE
        if ($request->has('course_id')) {
            $course = Course::with(['assignments', 'students'])->findOrFail($request->course_id);

            // Authorization: Ensure instructor teaches this course
            if ($user->isInstructor() && $course->instructor_id !== $user->Academic_ID) {
                abort(403, 'Unauthorized');
            }

            // Load all submissions for this course to populate the grid
            // We want a structure like: $submissions[student_id][assignment_id] = grade
            $rawSubmissions = \App\Models\Submission::whereIn('assignment_id', $course->assignments->pluck('id'))->get();
            
            $gradeMatrix = [];
            foreach ($rawSubmissions as $sub) {
                $gradeMatrix[$sub->student_id][$sub->assignment_id] = $sub;
            }

            return view('instructor.gradebook.index', [
                'selectedCourse' => $course,
                'gradeMatrix' => $gradeMatrix
            ]);
        }

        // STATE 2: LIST COURSES (Selection Menu)
        $courses = $user->teachingCourses;

        return view('instructor.gradebook.index', compact('courses'));
    }
}
