<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\Traits\LogsActivity;


class AttendanceController extends Controller
{

    use LogsActivity;

    // ----------------------------------------------------------------------
    // INSTRUCTOR METHODS
    // ----------------------------------------------------------------------

    /**
     * Display attendance management for instructor.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isInstructor()) {
            // Instructor View
            $courses = Course::where('instructor_id', $user->Academic_ID)->get();
            $selectedCourse = null;
            $students = collect();
            $attendances = collect();
            $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));

            if ($request->has('course_id')) {
                $selectedCourse = $courses->firstWhere('id', $request->course_id);

                if ($selectedCourse) {
                    $students = $selectedCourse->students;

                    // Get attendance records for this date
                    $attendances = Attendance::where('course_id', $selectedCourse->id)
                        ->whereDate('date', $selectedDate)
                        ->get()
                        ->keyBy('student_id');
                }
            }

            return view('instructor.attendance.index', compact('courses', 'selectedCourse', 'students', 'attendances', 'selectedDate'));
        }

        // Student View (Redirect or handle separately)
        return redirect()->route('student.attendance.index');
    }

    /**
     * Store or update attendance for a class session.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'in:present,absent,late,excused',
        ]);

        $course = Course::findOrFail($request->course_id);

        // Verify instructor owns the course
        if ($course->instructor_id !== Auth::user()->Academic_ID) {
            abort(403);
        }

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'student_id' => $studentId,
                    'date' => $request->date,
                ],
                [
                    'status' => $status,
                    'notes' => $request->notes[$studentId] ?? null,
                ]
            );
        }

        $this->recordLog('created', $course);

        return back()->with('success', 'Attendance updated successfully.');
    }

    // ----------------------------------------------------------------------
    // STUDENT METHODS
    // ----------------------------------------------------------------------

    public function studentIndex(Request $request)
    {
        $student = Auth::user();

        if (!$student->isStudent()) {
            abort(403);
        }

        $enrolledCourses = $student->enrolledCourses;
        $selectedCourse = null;
        $attendanceRecords = collect();
        $stats = [
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'excused' => 0,
            'total' => 0,
            'percentage' => 0
        ];

        if ($request->has('course_id')) {
            $selectedCourse = $enrolledCourses->firstWhere('id', $request->course_id);

            if ($selectedCourse) {
                $attendanceRecords = Attendance::where('course_id', $selectedCourse->id)
                    ->where('student_id', $student->Academic_ID)
                    ->orderBy('date', 'desc')
                    ->get();

                $stats['present'] = $attendanceRecords->where('status', 'present')->count();
                $stats['absent'] = $attendanceRecords->where('status', 'absent')->count();
                $stats['late'] = $attendanceRecords->where('status', 'late')->count();
                $stats['excused'] = $attendanceRecords->where('status', 'excused')->count();
                $stats['total'] = $attendanceRecords->count();

                if ($stats['total'] > 0) {
                    // Simple calculation: Present + Late (maybe partial?)
                    // Let's count Present as 1, Late as 0.5? Or just count Present.
                    // Usually Late counts as Present but maybe with penalty. Let's just do raw counts for now.
                    $stats['percentage'] = round(($stats['present'] / $stats['total']) * 100);
                }
            }
        }

        return view('student.attendance.index', compact('enrolledCourses', 'selectedCourse', 'attendanceRecords', 'stats'));
    }
}
