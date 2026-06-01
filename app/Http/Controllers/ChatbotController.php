<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Setting;
use App\Models\User;
use App\Models\Department;
use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Material;
use App\Models\Progress;
use App\Models\Semester;
use App\Models\Announcement;
use App\Models\Attendance;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $message = $request->message;
        $messageLower = strtolower($message);

        // 1. HANDLE ADMIN COMMANDS
        if ($user->isAdmin()) {
            if (str_contains($messageLower, 'close registration')) {
                Setting::setValue('registration_open', '0');
                return response()->json(['reply' => "✅ Command executed: Student Registration is now **CLOSED**."]);
            }
            if (str_contains($messageLower, 'open registration')) {
                Setting::setValue('registration_open', '1');
                return response()->json(['reply' => "✅ Command executed: Student Registration is now **OPEN**."]);
            }
        }

        // 2. GATHER CONTEXT
        $contextData = $this->buildContextData($user);

        // 3. CALL GOOGLE GEMINI
        $aiResponse = $this->askGemini($user, $message, $contextData);

        return response()->json(['reply' => $aiResponse]);
    }

    private function buildContextData($user)
    {
        $context = "";

        // Get all database information (except users table)
        $context .= $this->getAllDatabaseInfo();

        if ($user->isStudent()) {
            // --- STUDENT CONTEXT ---

            // 1. Enrolled Courses
            $courses = $user->enrolledCourses->pluck('title')->implode(', ');
            $context .= "Your Enrolled Courses: " . ($courses ?: "None") . ".\n";

            // 2. Attendance/Absences
            $absences = Attendance::where('student_id', $user->Academic_ID)->where('status', 'absent')->count();
            $attendance = Attendance::where('student_id', $user->Academic_ID)->where('status', 'present')->count();
            $context .= "Your Attendance: {$attendance} present, {$absences} absent.\n";

            // 3. Pending Assignments (Not submitted yet)
            $enrolledCourseIds = $user->enrolledCourses->pluck('id');
            $submittedAssignmentIds = Submission::where('student_id', $user->Academic_ID)->pluck('assignment_id');

            $pendingAssignments = Assignment::whereIn('course_id', $enrolledCourseIds)
                ->whereNotIn('id', $submittedAssignmentIds)
                ->where('due_date', '>=', now())
                ->take(5)
                ->get();

            if ($pendingAssignments->count() > 0) {
                $context .= "Your Pending Assignments: ";
                foreach ($pendingAssignments as $a) {
                    $context .= "{$a->title} (Due: {$a->due_date->format('M d')}), ";
                }
                $context .= "\n";
            }

            // 4. Your Submissions
            $mySubmissions = Submission::where('student_id', $user->Academic_ID)->with('assignment')->latest()->take(10)->get();
            if ($mySubmissions->count() > 0) {
                $context .= "Your Recent Submissions: ";
                foreach ($mySubmissions as $sub) {
                    $status = $sub->grade !== null ? "Graded ({$sub->grade}/{$sub->assignment->max_score})" : "Pending";
                    $context .= "{$sub->assignment->title}: {$status}, ";
                }
                $context .= "\n";
            }

            // 5. Recent Grades
            $grades = Submission::where('student_id', $user->Academic_ID)
                ->whereNotNull('grade')
                ->with('assignment')
                ->latest()
                ->take(5)
                ->get();

            if ($grades->count() > 0) {
                $context .= "Your Recent Grades: ";
                foreach ($grades as $g) {
                    $context .= "{$g->assignment->title}: {$g->grade}/{$g->assignment->max_score}, ";
                }
                $context .= "\n";
            }
        } elseif ($user->isInstructor()) {
            // --- INSTRUCTOR CONTEXT ---
            $courses = $user->teachingCourses->pluck('title')->implode(', ');
            $context .= "You teach: " . ($courses ?: "None") . ".\n";

            $assignmentIds = Assignment::whereIn('course_id', $user->teachingCourses->pluck('id'))->pluck('id');
            $pendingCount = Submission::whereIn('assignment_id', $assignmentIds)->whereNull('grade')->count();

            $context .= "You have {$pendingCount} student submissions waiting to be graded.\n";
        } elseif ($user->isAdmin()) {
            // --- ADMIN CONTEXT ---
            $sCount = User::where('role', 'student')->count();
            $iCount = User::where('role', 'instructor')->count();
            $context .= "System Overview: {$sCount} Students, {$iCount} Instructors.\n";
        }

        return $context;
    }

    private function getAllDatabaseInfo()
    {
        $context = "=== DATABASE INFORMATION ===\n\n";

        // 1. DEPARTMENTS
        $departments = Department::all();
        if ($departments->count() > 0) {
            $context .= "DEPARTMENTS:\n";
            foreach ($departments as $dept) {
                $context .= "  - {$dept->name}\n";
            }
            $context .= "\n";
        }

        // 2. COURSES (with departments and classrooms)
        $courses = Course::with('department')->get();
        if ($courses->count() > 0) {
            $context .= "COURSES:\n";
            foreach ($courses as $course) {
                $dept = $course->department ? $course->department->name : "N/A";
                $classroom = $course->classroom ?: "TBD";
                $context .= "  - {$course->title} (Code: {$course->course_code}, Department: {$dept}, Classroom: {$classroom})\n";
            }
            $context .= "\n";
        }

        // 3. SEMESTERS (with active status)
        $semesters = Semester::all();
        if ($semesters->count() > 0) {
            $context .= "SEMESTERS:\n";
            $now = now();
            foreach ($semesters as $sem) {
                $startDate = $sem->start_date ? \Carbon\Carbon::parse($sem->start_date) : null;
                $endDate = $sem->end_date ? \Carbon\Carbon::parse($sem->end_date) : null;

                // Check if semester is active (current date is between start and end)
                $isActive = ($startDate && $endDate && $now->between($startDate, $endDate)) ? "ACTIVE" : "INACTIVE";

                $context .= "  - {$sem->name} (Start: {$sem->start_date}, End: {$sem->end_date}) [{$isActive}]\n";
            }
            $context .= "\n";
        }

        // 4. CLASSROOMS
        $classrooms = Classroom::all();
        if ($classrooms->count() > 0) {
            $context .= "CLASSROOMS:\n";
            foreach ($classrooms as $room) {
                $context .= "  - {$room->name} (Building: {$room->building}, Capacity: {$room->capacity})\n";
            }
            $context .= "\n";
        }

        // 5. MATERIALS
        $materials = Material::with('course')->limit(10)->get();
        if ($materials->count() > 0) {
            $context .= "COURSE MATERIALS (Sample):\n";
            foreach ($materials as $mat) {
                $course = $mat->course ? $mat->course->title : "N/A";
                $context .= "  - {$mat->title} (Course: {$course}, Type: {$mat->type})\n";
            }
            $context .= "\n";
        }

        // 6. ASSIGNMENTS (with courses)
        $assignments = Assignment::with('course')->limit(15)->get();
        if ($assignments->count() > 0) {
            $context .= "ASSIGNMENTS (Sample):\n";
            foreach ($assignments as $assign) {
                $course = $assign->course ? $assign->course->title : "N/A";
                $context .= "  - {$assign->title} (Course: {$course}, Due: {$assign->due_date->format('M d, Y')}, Max Score: {$assign->max_score})\n";
            }
            $context .= "\n";
        }

        // 7. ANNOUNCEMENTS
        $announcements = Announcement::latest()->limit(10)->get();
        if ($announcements->count() > 0) {
            $context .= "ANNOUNCEMENTS (Latest):\n";
            foreach ($announcements as $ann) {
                $context .= "  - {$ann->title} (Posted: {$ann->created_at->format('M d, Y')})\n";
            }
            $context .= "\n";
        }

        // 8. ENROLLMENT STATS
        $enrollmentTotal = Enrollment::count();
        $context .= "ENROLLMENT STATS: {$enrollmentTotal} total enrollments\n";

        // 9. SUBMISSION STATS
        $submissionTotal = Submission::count();
        $graded = Submission::whereNotNull('grade')->count();
        $pending = Submission::whereNull('grade')->count();
        $context .= "SUBMISSION STATS: {$submissionTotal} total ({$graded} graded, {$pending} pending)\n";

        // 10. ATTENDANCE STATS
        $presentTotal = Attendance::where('status', 'present')->count();
        $absentTotal = Attendance::where('status', 'absent')->count();
        $context .= "ATTENDANCE STATS: {$presentTotal} present, {$absentTotal} absent\n\n";

        return $context;
    }

    private function askGemini($user, $userMessage, $contextData)
    {
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return "System Error: API Key missing.";
        }

        // Using gemini-2.5-flash model
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";
        $systemPrompt = "You are a helpful LMS Assistant called LMS Assistant for a Learning Management System. When students ask about courses, assignments, or grades, refer to the specific data provided in the Context section below. Answer questions directly using the actual data provided, not general advice.\n\nContext:\n" . $contextData . "\n\nInstructions: Answer all questions based on the context provided above. If a student asks 'what courses can i enroll in?', list the courses from the 'Available Courses to Enroll In' section. If they ask about their assignments, grades, or enrolled courses, use the data provided.";

        try {
            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nUser: " . $userMessage]
                        ]
                    ]
                ]
            ]);

            $json = $response->json();

            \Illuminate\Support\Facades\Log::info('Gemini API Response:', $json);

            // Check if response has an error
            if (isset($json['error'])) {
                \Illuminate\Support\Facades\Log::error('Gemini API Error:', $json['error']);
                return "API Error: " . ($json['error']['message'] ?? "Unknown error");
            }

            return $json['candidates'][0]['content']['parts'][0]['text'] ?? "I'm having trouble thinking right now.";
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gemini Exception:', ['message' => $e->getMessage()]);
            return "Connection error: " . $e->getMessage();
        }
    }
}
