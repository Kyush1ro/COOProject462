<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\LogsActivity;
use App\Http\Controllers\Traits\NotifiesN8n;

class AnnouncementController extends Controller
{

    use LogsActivity;
    use NotifiesN8n;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // STATE 1: SHOW ANNOUNCEMENTS FOR A SPECIFIC COURSE
        if ($request->has('course_id')) {
            $course = Course::findOrFail($request->course_id);

            // Authorization
            if ($user->isInstructor() && $course->instructor_id !== $user->Academic_ID) {
                abort(403, 'Unauthorized');
            }
            if ($user->isStudent() && !$user->enrolledCourses->contains($course->id)) {
                abort(403, 'Unauthorized');
            }

            $announcements = $course->announcements()->orderBy('created_at', 'desc')->get();

            // Mark all as read for student
            if ($user->isStudent()) {
                foreach ($announcements as $announcement) {
                    // Sync without detaching to ensure we don't duplicate or remove others (though unique constraint handles duplication)
                    // Actually, simpler to just attach if not exists.
                    // But syncWithoutDetaching is cleaner.
                    $announcement->views()->syncWithoutDetaching([$user->Academic_ID]);
                }
            }

            return view('announcements.index', [
                'selectedCourse' => $course,
                'announcements' => $announcements
            ]);
        }

        // STATE 2: LIST COURSES (Selection Menu)
        $courses = collect();

        if ($user->isInstructor()) {
            $courses = $user->teachingCourses()->withCount('announcements')->get();
        } elseif ($user->isStudent()) {
            // We need to count UNREAD announcements
            // Unread = Total Announcements - Viewed Announcements
            // This is a bit complex in Eloquent. 
            // Alternative: Load all courses, then iterate and count.
            $courses = $user->enrolledCourses;

            foreach ($courses as $course) {
                $total = $course->announcements()->count();
                $viewed = \Illuminate\Support\Facades\DB::table('announcement_views')
                    ->where('user_id', $user->Academic_ID)
                    ->whereIn('announcement_id', $course->announcements()->pluck('id'))
                    ->count();
                $course->unread_announcements_count = $total - $viewed;
                $course->announcements_count = $total;
            }
        } elseif ($user->isAdmin()) {
            $courses = Course::withCount('announcements')->get();
        }

        return view('announcements.index', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isInstructor()) {
            abort(403, 'Only instructors can create announcements.');
        }

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if ($course->instructor_id !== $user->Academic_ID) {
            abort(403, 'You do not teach this course.');
        }

        $announcement = Announcement::create($validated);

        $this->recordLog('created', $announcement);

        $students = $course->students()->pluck('email')->toArray();
        $this->sendToN8n('announcement', $announcement, $students);
        
        return back()->with('success', 'Announcement posted successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isInstructor()) {
            abort(403);
        }

        if ($announcement->course->instructor_id !== $user->Academic_ID) {
            abort(403);
        }

        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully.');
    }
}
