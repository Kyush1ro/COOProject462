<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // 1. Security Check
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized. Only Admins can view reports.');
        }

        // --- KPI CARDS DATA ---
        $totalUsers = User::count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();
        $totalAssignments = \App\Models\Assignment::count();

        // --- CHART 1: User Roles Distribution (Pie Chart) ---
        // Example Result: ['student' => 50, 'instructor' => 5]
        $rolesData = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        // --- CHART 2: Top 5 Popular Courses (Bar Chart) ---
        // Get courses with the highest number of students enrolled
        $popularCourses = Course::withCount('students')
            ->orderBy('students_count', 'desc')
            ->take(5)
            ->get();

        // --- CHART 3: Enrollment Growth (Line Chart) ---
        // Get enrollments per month for the last 6 months
        // Note: Using a query compatible with MySQL
        $enrollmentGrowth = Enrollment::select(
            DB::raw('count(id) as count'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_label")
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month_label')
            ->orderBy('month_label', 'asc')
            ->get();

        return view('admin.reports.index', compact(
            'totalUsers',
            'totalCourses',
            'totalEnrollments',
            'totalAssignments',
            'rolesData',
            'popularCourses',
            'enrollmentGrowth'
        ));
    }
}
