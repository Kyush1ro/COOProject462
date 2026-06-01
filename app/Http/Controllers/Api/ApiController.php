<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ProgressResource;
use App\Http\Resources\StudentPerformanceResource;
use App\Models\Course;
use App\Models\Progress;
use App\Models\Department;
use App\Models\Semester;
use App\Models\Material;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\SemesterResource;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\AssignmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    // 0. POST /login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // 1. GET /courses
    public function index()
    {
        $courses = Course::with('instructor')->get();
        return CourseResource::collection($courses);
    }

    // 2. GET /course/{id}
    public function show($id)
    {
        $course = Course::with(['instructor', 'students'])->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return new CourseResource($course);
    }

    // 3. POST /progress/update
    public function updateProgress(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'percentage' => 'required|integer|min:0|max:100',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $progress = Progress::updateOrCreate(
            [
                'student_id' => $user->Academic_ID, 
                'course_id'  => $request->course_id,
            ],
            [
                'percentage'     => $request->percentage,
                'last_access_at' => now(),
            ]
        );

        // USE THE RESOURCE HERE
        return new ProgressResource($progress);
    }

    // 4. GET /student/performance
    public function studentPerformance()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $stats = [
            'student' => $user,
            'enrolled_count' => $user->enrolledCourses()->count(),
            'submissions_count' => $user->submissions()->count(),
            'average_progress' => round(
                Progress::where('student_id', $user->Academic_ID)->avg('percentage') ?? 0, 
                2
            ),
        ];

        // USE THE RESOURCE HERE
        return new StudentPerformanceResource($stats);
    }

    // 5. POST /enroll
    public function enroll(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            return response()->json(['message' => 'Only students can enroll in courses.'], 403);
        }

        if (\App\Models\Setting::getValue('enrollment_open') !== '1') {
            return response()->json(['message' => 'Course enrollment is currently closed.'], 403);
        }

        $course = Course::find($request->course_id);

        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'You are already enrolled in this course.'], 409);
        }

        $user->enrolledCourses()->attach($course->id);

        // Optional: Trigger n8n webhook (copied from EnrollmentController)
        $n8nWebhookUrl = 'https://your-n8n-instance.com/webhook/welcome-email';
        try {
            \Illuminate\Support\Facades\Http::post($n8nWebhookUrl, [
                'student_name' => $user->name,
                'student_email' => $user->email,
                'course_title' => $course->title,
                'event' => 'enrollment'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('n8n Webhook Failed: ' . $e->getMessage());
        }

        // Return the enrollment record (we need to fetch it since attach doesn't return it)
        $enrollment = \App\Models\Enrollment::where('student_id', $user->Academic_ID)
            ->where('course_id', $course->id)
            ->first();

        return new \App\Http\Resources\EnrollmentResource($enrollment);
    }

    // 6. POST /submission
    public function submitAssignment(Request $request)
    {
        $request->validate([
            'submission_file' => 'required|file|mimes:pdf,doc,docx,zip|max:5000',
            'assignment_id' => 'required|exists:assignments,id',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Optional: Check if user is student
        // if (!$user->isStudent()) { ... }

        $assignment = \App\Models\Assignment::findOrFail($request->assignment_id);

        // Store the file
        $file = $request->file('submission_file');
        $filePath = $file->store('submissions', 'public');

        $submission = \App\Models\Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => $user->Academic_ID,
            'file_path' => $filePath,
            'original_filename' => $file->getClientOriginalName(),
        ]);

        return new \App\Http\Resources\SubmissionResource($submission);
    }

    // 7. POST /announcement
    public function createAnnouncement(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isInstructor()) {
            return response()->json(['message' => 'Only instructors can create announcements.'], 403);
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if ($course->instructor_id !== $user->Academic_ID) {
            return response()->json(['message' => 'You do not teach this course.'], 403);
        }

        $announcement = \App\Models\Announcement::create($validated);

        return new \App\Http\Resources\AnnouncementResource($announcement);
    }

    // 8. GET /assignments
    public function getAssignments(Request $request)
    {
        $query = \App\Models\Assignment::query();

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        return AssignmentResource::collection($query->get());
    }

    // 9. GET /materials
    public function getMaterials(Request $request)
    {
        $query = Material::query();

        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        return MaterialResource::collection($query->get());
    }

    // 10. GET /departments
    public function getDepartments()
    {
        return DepartmentResource::collection(Department::all());
    }

    // 11. GET /semesters
    public function getSemesters()
    {
        return SemesterResource::collection(Semester::all());
    }
}