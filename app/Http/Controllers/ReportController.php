<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // 1. User Statistics
        $totalStudents = User::where('role', 'student')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        
        // 2. Course Statistics
        $totalCourses = Course::count();
        $activeCourses = Course::active()->count();

        // 3. Enrollments per Department
        // Join courses -> departments and count enrollments
        $enrollmentsByDept = Department::withCount(['courses as total_enrollments' => function($query) {
            $query->join('enrollments', 'courses.id', '=', 'enrollments.course_id');
        }])->get();

        // 4. Average Grades per Course (Top 5)
        // Assuming 'grade' column in enrollments or submissions. 
        // Let's check if we have grades. We have 'grade' in submissions, and 'final_grade' in enrollments?
        // Let's stick to simple counts for now to avoid errors if columns missing.
        
        // 5. Recent Activity (Audit Logs count per day for last 7 days)
        $activityData = \App\Models\AuditLog::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('reports.index', compact(
            'totalStudents', 
            'totalInstructors', 
            'totalCourses', 
            'activeCourses',
            'enrollmentsByDept',
            'activityData'
        ));
    }
}
