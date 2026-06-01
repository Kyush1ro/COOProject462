<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Traits\LogsActivity;
use App\Http\Controllers\Traits\NotifiesN8n;



class MaterialController extends Controller
{

    use LogsActivity;
    use NotifiesN8n;


    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Security: Ensure user is Instructor or Admin
        if (!$user->isInstructor() && !$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        // STATE 1: SHOW MATERIALS FOR A SPECIFIC COURSE
        if ($request->has('course_id')) {
            $course = Course::findOrFail($request->course_id);

            // Authorization check
            if ($user->isInstructor() && $course->instructor_id !== $user->Academic_ID) {
                abort(403, 'Unauthorized');
            }

            $course->load(['materials' => function ($q) {
                $q->latest();
            }]);

            return view('materials.index', [
                'selectedCourse' => $course
            ]);
        }

        // STATE 2: LIST COURSES
        $courses = $user->teachingCourses()->withCount('materials')->get();

        return view('materials.index', compact('courses'));
    }

    public function store(Request $request, Course $course)
    {
        $user = Auth::user(); // Needed for n8n payload

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'file'  => 'required|file|mimes:pdf,doc,docx,ppt,pptx,mp4,zip|max:20480',
        ]);

        $file = $request->file('file');
        $path = $file->store('materials', 'public');

        Material::create([
            'course_id' => $course->id,
            'title'     => $data['title'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
        ]);


        // Send email notification to all enrolled students
        $studentEmails = $course->students()->pluck('email')->toArray();

        if (!empty($studentEmails)) {
            $this->sendToN8n('material', [
                'title' => $data['title'],
                'course' => $course->title,
                'file_type' => $file->getClientOriginalExtension(),
                'instructor_name' => $user->name,
            ], $studentEmails);
        }
        return back()->with('success', 'Material uploaded successfully.');
    }

    public function download(Material $material)
    {
        // Check authorization if needed (e.g., user is enrolled or instructor)

        $filePath = $material->file_path;

        if (!Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File not found on server.');
        }

        $fileName = $material->original_filename ?? $material->title . '.' . $material->file_type;

        return Storage::disk('public')->download($filePath, $fileName);
    }

    public function destroy(Material $material)
    {
        Storage::disk('public')->delete($material->file_path);
        $material->delete();

        return back()->with('success', 'Material deleted successfully.');
    }
}
