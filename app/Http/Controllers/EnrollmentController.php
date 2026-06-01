<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // Required for n8n
use App\Http\Controllers\Traits\LogsActivity;

class EnrollmentController extends Controller
{

    use LogsActivity;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        //
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isStudent()) {
            abort(403, 'Only students can enroll in courses.');
        }

        if (\App\Models\Setting::getValue('enrollment_open') !== '1') {
            return back()->with('error', 'Course enrollment is currently closed.');
        }

        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'You are already enrolled in this course.');
        }
        $user->enrolledCourses()->attach($course->id);
        $this->recordLog('enrolled', $course);


        $n8nWebhookUrl = 'https://your-n8n-instance.com/webhook/welcome-email';

        try {
            Http::post($n8nWebhookUrl, [
                'student_name' => $user->name,
                'student_email' => $user->email,
                'course_title' => $course->title,
                'event' => 'enrollment'
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Illuminate\Support\Facades\Log::error('n8n Webhook Failed: ' . $e->getMessage());
        }
        // ----------------------------------

        return back()->with('success', 'You have been enrolled in the course successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $courseId)
    {
        $dropDeadline = \App\Models\Setting::getValue('drop_deadline');
        if ($dropDeadline && now()->gt(\Carbon\Carbon::parse($dropDeadline))) {
            return back()->with('error', 'The deadline to drop courses has passed.');
        }

        $user = Auth::user();
        $enrollment = Enrollment::where('student_id', $user->Academic_ID)
            ->where('course_id', $courseId)
            ->first();

        if ($enrollment) {
            $enrollment->delete();
            return redirect()->route('courses.index')->with('success', 'Course dropped successfully');
        }


        return back()->with('error', 'Enrollment not found.');
    }
}
