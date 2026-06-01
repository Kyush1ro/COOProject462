<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\LogsActivity;

class CourseController extends Controller
{
    use LogsActivity;

    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index(Request $request, $departmentCode = null)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $baseQuery = Course::with(['instructor', 'department']);

        // 1. FILTERING LOGIC
        if ($departmentCode) {
            $baseQuery->whereHas('department', function ($query) use ($departmentCode) {
                $query->where('code', $departmentCode);
            });
        }

        // 2. ROLE-BASED LOGIC
        if ($user->isAdmin()) {
            $courses = $baseQuery->get();
        } elseif ($user->isInstructor()) {
            // Note: This requires complex merging or a different view. For simplicity,
            // we will let the instructor see ALL their courses OR the filtered view.
            $courses = $baseQuery->where('instructor_id', $user->Academic_ID)->get();
        } else {
            // Student sees only enrolled courses
            $courses = $baseQuery->whereHas('students', function ($query) use ($user) {
                $query->where('users.Academic_ID', $user->Academic_ID);
            })->get();
        }

        return view('courses.index', compact('courses'));
    }

    public function available()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Get IDs of courses the user is already in
        $enrolledIds = $user->enrolledCourses->pluck('id');

        // Fetch courses appearing in the 'courses' table BUT NOT in 'enrolledIds'
        // AND belonging to the active semester
        $courses = Course::whereNotIn('id', $enrolledIds)
            ->active() // Use the scopeActive we defined earlier
            ->get();

        return view('courses.available', compact('courses'));
    }

    private function getClassrooms()
    {
        // Fetch classrooms that are NOT assigned to any course (course_id is null)
        return \App\Models\Classroom::whereNull('course_id')->pluck('name', 'id');
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        $instructors = User::where('role', 'instructor')->get();
        $classrooms = $this->getClassrooms();
        $departments = \App\Models\Department::all();

        return view('courses.create', compact('instructors', 'classrooms', 'departments'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isAdmin() && ! $user->isInstructor()) {
            abort(403);
        }

        $rules = [
            'title'       => 'required|string|max:255',
            'course_code' => 'required|string', // Unique check done manually later
            'classroom'   => 'required|exists:classrooms,id', // Validate ID exists
            'course_type' => 'required|in:theory,lab',
            'department_id' => 'required|exists:departments,id',
        ];

        if ($user->isAdmin()) {
            $rules['instructor_id'] = 'required|exists:users,Academic_ID';
        }

        $validated = $request->validate($rules);

        // Get the classroom ID
        $classroomId = $validated['classroom'];
        
        // Find the classroom to get its name (for legacy 'classroom' column support if needed)
        // or just to verify it's still free.
        $classroom = \App\Models\Classroom::findOrFail($classroomId);
        
        if ($classroom->course_id !== null) {
             return back()->withErrors(['classroom' => 'This classroom has already been reserved.'])->withInput();
        }

        // We store the classroom NAME in the course table for now (legacy support)
        // Ideally we should migrate 'classroom' column to 'classroom_id' FK in courses table.
        // But per instructions, we are linking classroom -> course.
        $validated['classroom'] = $classroom->name;

        // Prepend Department Code to Course Code
        $department = \App\Models\Department::findOrFail($validated['department_id']);
        $validated['course_code'] = $department->code . $validated['course_code'];

        // Check uniqueness again after concatenation
        if (\App\Models\Course::where('course_code', $validated['course_code'])->exists()) {
             return back()->withErrors(['course_code' => 'The course code ' . $validated['course_code'] . ' has already been taken.'])->withInput();
        }

        if ($user->isInstructor()) {
            $validated['instructor_id'] = $user->Academic_ID;
        }

        // Assign to Active Semester
        $activeSemester = \App\Models\Semester::getActive();
        if ($activeSemester) {
            $validated['semester_id'] = $activeSemester->id;
        } else {
            // Fallback or error? For now, let's allow null but maybe log a warning or error.
            // Or just fail. A system with semesters should have an active one.
            return back()->withErrors(['general' => 'No active semester found. Please contact admin.'])->withInput();
        }

        $newCourse = Course::create($validated);

        // Reserve the classroom
        $classroom->update(['course_id' => $newCourse->id]);

        $this->recordLog('created', $newCourse);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load(['assignments', 'instructor', 'materials', 'department']);

        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        $instructors = User::where('role', 'instructor')->get();
        $classrooms = $this->getClassrooms();
        $departments = \App\Models\Department::all();

        return view('courses.edit', compact('course', 'instructors', 'classrooms', 'departments'));
    }

    public function update(Request $request, Course $course)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        $rules = [
            'title'       => 'required|string|max:255',
            'course_code' => 'required|string', // Unique check done manually
            'classroom'   => 'required|exists:classrooms,id',
            'course_type' => 'required|in:theory,lab',
            'department_id' => 'required|exists:departments,id',
        ];

        if ($user->isAdmin()) {
            $rules['instructor_id'] = 'required|exists:users,Academic_ID';
        }

        $validated = $request->validate($rules);

        // Handle Classroom
        $classroomId = $validated['classroom'];
        $classroom = \App\Models\Classroom::findOrFail($classroomId);
        
        // If changing classroom, check if new one is free (unless it's the SAME classroom already assigned to this course)
        if ($classroom->course_id !== null && $classroom->course_id !== $course->id) {
             return back()->withErrors(['classroom' => 'This classroom has already been reserved.'])->withInput();
        }
        
        // Release old classroom if different
        if ($course->classroom && $course->classroom()->exists()) {
            $oldClassroom = $course->classroom()->first();
            if ($oldClassroom->id !== $classroom->id) {
                $oldClassroom->update(['course_id' => null]);
            }
        }

        $validated['classroom'] = $classroom->name;

        // Handle Course Code Prefix
        $department = \App\Models\Department::findOrFail($validated['department_id']);
        $fullCode = $department->code . $validated['course_code'];
        
        // Check uniqueness (ignoring current course)
        if (\App\Models\Course::where('course_code', $fullCode)->where('id', '!=', $course->id)->exists()) {
             return back()->withErrors(['course_code' => 'The course code ' . $fullCode . ' has already been taken.'])->withInput();
        }
        
        $validated['course_code'] = $fullCode;

        if ($user->isInstructor()) {
            $validated['instructor_id'] = $user->Academic_ID;
        }

        $course->update($validated);
        
        // Reserve new classroom
        $classroom->update(['course_id' => $course->id]);

        // Note: getChanges() might be cleared if we do other things? 
        // But here it should be fine as long as we call it right after update.
        // However, $course->update() refreshes the model? No, it keeps changes in getChanges() until next sync.
        $this->recordLog('updated', $course);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->isAdmin() && ! ($user->isInstructor() && $course->instructor_id === $user->Academic_ID)) {
            abort(403);
        }

        $this->recordLog('deleted', $course);

        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
