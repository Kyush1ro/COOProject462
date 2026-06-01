<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;

class CalendarController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ensure user is a student
        if (!$user->isStudent()) {
            abort(403, 'Only students can view this calendar.');
        }

        // Get enrolled courses
        $enrolledCourses = $user->enrolledCourses;
        $courseIds = $enrolledCourses->pluck('id');

        // Get assignments for these courses
        $assignments = Assignment::whereIn('course_id', $courseIds)
            ->with('course')
            ->get();

        // Format events for FullCalendar
        $events = $assignments->map(function ($assignment) {
            return [
                'title' => $assignment->course->course_code . ': ' . $assignment->title,
                'start' => $assignment->due_date,
                'url' => route('assignments.show', $assignment->id),
                'backgroundColor' => '#3b82f6', // Blue
                'borderColor' => '#2563eb',
            ];
        });

        return view('student.calendar.index', compact('events'));
    }
}
