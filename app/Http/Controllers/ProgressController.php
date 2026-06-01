<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * Display the student's progress page.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ensure only students can access this page
        if (!$user->isStudent()) {
            abort(403, 'Only students can view personal progress.');
        }

        // Fetch courses the student is enrolled in
        // We eagerly load the 'instructor' to display their name if needed
        $courses = $user->enrolledCourses()->with('instructor')->get();

        // Map over courses to attach the progress percentage
        $courses->map(function ($course) use ($user) {
            $progress = Progress::where('student_id', $user->Academic_ID)
                                ->where('course_id', $course->id)
                                ->first();
            
            // Add a temporary 'current_progress' attribute to the course object
            $course->current_progress = $progress ? $progress->percentage : 0;
            return $course;
        });

        return view('student.progress', compact('courses'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'percentage' => 'required|integer|min:0|max:100',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $progress = Progress::updateOrCreate(
            [
                'student_id' => $user->Academic_ID, // Using custom Academic_ID
                'course_id'  => $course->id,
            ],
            [
                'percentage'    => $data['percentage'],
                'last_access_at'=> now(),
            ]
        );

        $n8nWebhookUrl = 'https://your-n8n-instance.com/webhook/progress-check';

        // Optional: Only trigger if progress is low (< 50%) to limit traffic, 
        // or send every time and let n8n decide.
        try {
            Http::post($n8nWebhookUrl, [
                'student_name' => $user->name,
                'student_email' => $user->email,
                'course_title' => $course->title,
                'progress_percentage' => $progress->percentage,
                'event' => 'progress_updated'
            ]);
        } catch (\Exception $e) {
            // Log the error silently so it doesn't break the user's experience
            \Illuminate\Support\Facades\Log::error('n8n Progress Webhook Failed: ' . $e->getMessage());
        }
        // --------------------------------------------------

        return response()->json([
            'status'    => 'ok',
            'progress'  => $progress->percentage,
        ]);
    }
}