<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isStudent()) {
            return $this->studentDashboard($user);
        } elseif ($user->isInstructor()) {
            return $this->instructorDashboard($user);
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard($user);
        }

        return view('dashboard'); // Fallback
    }

    private function studentDashboard($user)
    {
        // 1. Enrolled Courses Count
        $enrolledCourses = $user->enrolledCourses;
        $coursesCount = $enrolledCourses->count();

        // 2. Upcoming Assignments (Due in the future)
        $upcomingAssignments = Assignment::whereIn('course_id', $enrolledCourses->pluck('id'))
            ->where('due_date', '>', now())
            ->whereDoesntHave('submissions', function ($query) use ($user) {
                $query->where('student_id', $user->Academic_ID);
            })
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // 3. Recent Grades (Last 5 graded submissions)
        $recentGrades = Submission::where('student_id', $user->Academic_ID)
            ->whereNotNull('grade')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // 4. Attendance Overview (Overall percentage)
        // This is a bit heavy, maybe just show a summary or skip for now.
        // Let's show total assignments submitted vs total assignments due
        
        return view('dashboard.student', compact('coursesCount', 'upcomingAssignments', 'recentGrades'));
    }

    private function instructorDashboard($user)
    {
        // 1. Courses Taught
        $courses = Course::where('instructor_id', $user->Academic_ID)->get();
        $coursesCount = $courses->count();
        $courseIds = $courses->pluck('id');

        // 2. Total Students (Unique)
        $totalStudents = User::whereHas('enrolledCourses', function($q) use ($courseIds) {
            $q->whereIn('courses.id', $courseIds);
        })->count();

        // 3. Recent Submissions (Needing grading)
        $pendingSubmissions = Submission::whereIn('assignment_id', function($q) use ($courseIds) {
                $q->select('id')->from('assignments')->whereIn('course_id', $courseIds);
            })
            ->whereNull('grade')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. Upcoming Deadlines (Assignments created by instructor due soon)
        $upcomingDeadlines = Assignment::whereIn('course_id', $courseIds)
            ->where('due_date', '>', now())
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        return view('dashboard.instructor', compact('coursesCount', 'totalStudents', 'pendingSubmissions', 'upcomingDeadlines'));
    }

    private function adminDashboard($user)
    {
        // Simple stats for admin
        $usersCount = User::count();
        $coursesCount = Course::count();
        $assignmentsCount = Assignment::count();
        $submissionsCount = Submission::count();

        return view('dashboard.admin', compact('usersCount', 'coursesCount', 'assignmentsCount', 'submissionsCount'));
    }
}
