<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\LogsActivity;
use App\Http\Controllers\Traits\NotifiesN8n;

class AssignmentController extends Controller
{

    use LogsActivity;
    use NotifiesN8n;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // STATE 1: SHOW ASSIGNMENTS FOR A SPECIFIC COURSE
        if ($request->has('course_id')) {
            $course = Course::findOrFail($request->course_id);

            // Authorization: Ensure user is linked to this course
            if ($user->isInstructor() && $course->instructor_id !== $user->Academic_ID) {
                abort(403, 'Unauthorized');
            }
            if ($user->isStudent() && !$user->enrolledCourses->contains($course->id)) {
                abort(403, 'Unauthorized');
            }

            $assignments = $course->assignments()->orderBy('due_date', 'asc')->get();

            return view('assignments.index', [
                'selectedCourse' => $course,
                'assignments' => $assignments
            ]);
        }

        // STATE 2: LIST COURSES (Selection Menu)
        $courses = collect();

        if ($user->isInstructor()) {
            $courses = $user->teachingCourses()->withCount('assignments')->get();
        } elseif ($user->isStudent()) {
            $courses = $user->enrolledCourses()->withCount('assignments')->get();
        } elseif ($user->isAdmin()) {
            $courses = Course::withCount('assignments')->get();
        }

        return view('assignments.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security: Only Instructors can create assignments
        if (!$user->isInstructor()) {
            abort(403, 'Only instructors can create assignments.');
        }

        // Fetch courses taught by this instructor to populate the dropdown
        $courses = $user->teachingCourses;

        return view('assignments.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isInstructor()) {
            abort(403);
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:now',
            'max_score' => 'required|integer|min:1',
        ]);

        // Security check: Ensure the instructor owns the course
        $course = Course::findOrFail($validated['course_id']);

        if ($course->instructor_id !== $user->Academic_ID) {
            abort(403, 'You do not teach this course.');
        }

        $assignment = \App\Models\Assignment::create($validated);

        $this->recordLog('created', $assignment);

        $students = $assignment->course->students()->pluck('email')->toArray();
        $this->sendToN8n('assignment', $assignment, $students);

        return redirect()->route('assignments.index')->with('success', 'Assignment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Eager Load Dependencies
        $assignment->load(['course', 'submissions']);

        // 2. Authorization Check
        $isInstructor = ($assignment->course->instructor_id === $user->Academic_ID);
        $isStudent = $user->enrolledCourses->contains($assignment->course);
        $isAdmin = $user->isAdmin();

        if (!$isAdmin && !$isInstructor && !$isStudent) {
            abort(403, 'Unauthorized. You are not linked to this course.');
        }

        // 3. Filter Submissions for Students
        $submissions = $assignment->submissions;
        $userSubmission = null;

        if ($user->isStudent()) {
            $userSubmission = $submissions->where('student_id', $user->Academic_ID)->first();
        }

        // 4. Return View
        return view('assignments.show', [
            'assignment' => $assignment,
            'userSubmission' => $userSubmission,
            'submissions' => $submissions,
        ]);
    }

    public function edit(string $id)
    {
        // Placeholder
    }

    public function update(Request $request, string $id)
    {
        // Placeholder
    }

    public function destroy(Assignment $assignment)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Authorization: Only the instructor of the course can delete the assignment
        if ($user->Academic_ID !== $assignment->course->instructor_id) {
            abort(403, 'Unauthorized. Only the instructor can delete this assignment.');
        }

        $this->recordLog('deleted', $assignment);

        $assignment->delete();

        return back()->with('success', 'Assignment deleted successfully.');
    }
}
