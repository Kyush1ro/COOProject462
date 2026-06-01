<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\LogsActivity;


class SubmissionController extends Controller
{

    use LogsActivity;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // STATE 1: SHOW SUBMISSIONS FOR A SPECIFIC COURSE
        // STATE 1: SHOW SUBMISSIONS FOR A SPECIFIC COURSE
        if ($request->has('course_id')) {
            $course = Course::findOrFail($request->course_id);

            // Authorization: Ensure user is linked to this course
            if ($user->isInstructor() && $course->instructor_id !== $user->Academic_ID) {
                abort(403, 'Unauthorized');
            }
            if ($user->isStudent() && !$user->enrolledCourses->contains($course->id)) {
                abort(403, 'Unauthorized');
            }

            // Load assignments and submissions
            $course->load(['assignments' => function ($q) use ($request) {
                if ($request->has('assignment_id')) {
                    $q->where('id', $request->assignment_id);
                }
            }, 'assignments.submissions' => function ($q) use ($user) {
                if ($user->isStudent()) {
                    $q->where('student_id', $user->Academic_ID);
                }
                $q->with('student');
            }]);

            return view('submissions.index', [
                'selectedCourse' => $course,
                'selectedAssignmentId' => $request->assignment_id // Pass this to view
            ]);
        }

        // STATE 2: LIST COURSES (Selection Menu)
        if ($user->isInstructor()) {
            // We need to count submissions through assignments
            $courses = $user->teachingCourses()->with(['assignments' => function ($q) {
                $q->withCount('submissions');
            }])->get();
        } elseif ($user->isStudent()) {
            $courses = $user->enrolledCourses;
        } else {
            $courses = Course::all();
        }

        return view('submissions.index', compact('courses'));
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
    public function store(Request $request)
    {
        // Add a check: if (!Auth::user()->isStudent()) { abort(403); }

        $request->validate([
            'submission_file' => 'required|file|mimes:pdf,doc,docx,zip|max:5000',
            'assignment_id' => 'required|exists:assignments,id',
        ]);

        $assignment = Assignment::findOrFail($request->assignment_id);

        // 1. Store the file on the server (e.g., storage/app/public/submissions)
        $file = $request->file('submission_file');
        $filePath = $file->store('submissions', 'public');

        // 2. Create the submission record
        $newSubmission = Submission::create([
            'assignment_id' => $assignment->id,
            'student_id' => Auth::user()->Academic_ID, // Use the Academic_ID
            'file_path' => $filePath,
            'original_filename' => $file->getClientOriginalName(),
        ]);

        $this->recordLog('submitted', $newSubmission);


        return back()->with('success', 'Assignment submitted successfully!');
    }

    public function download(Submission $submission)
    {
        // Authorization: Ensure user is the student who submitted OR the instructor of the course
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isStudent = $user->isStudent() && $submission->student_id === $user->Academic_ID;
        $isInstructor = $user->isInstructor() && $submission->assignment->course->instructor_id === $user->Academic_ID;
        $isAdmin = $user->isAdmin();

        if (!$isAdmin && !$isStudent && !$isInstructor) {
            abort(403, 'Unauthorized');
        }
        $filePath = $submission->file_path;

        // 2. File Existence Check
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File not found on server.');
        }

        // 3. Filename Fallback
        $fileName = $submission->original_filename ?? 'submission_' . $submission->id . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

        // 4. LOG THE ACTION (Execute the log method BEFORE the return)
        // NOTE: This call is the correct location for the log.
        $this->recordLog('downloaded', $submission);
        return response()->download(\Illuminate\Support\Facades\Storage::disk('public')->path($filePath), $fileName);
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
    public function update(Request $request, Submission $submission)
    {
        // Add a check: if (!Auth::user()->isInstructor()) { abort(403); }

        $validated = $request->validate([
            'grade' => 'required|numeric|min:0|max:' . $submission->assignment->max_score,
            'feedback' => 'nullable|string',
        ]);

        // Security check: Ensure instructor teaches this course
        if ($submission->assignment->course->instructor_id !== Auth::user()->Academic_ID) {
            abort(403, 'Unauthorized to grade this submission.');
        }

        $submission->update($validated);

        $this->recordLog('graded', $submission);

        $this->sendToN8n('grade', $submission, $submission->student->email);

        return back()->with('success', 'Grade and feedback recorded successfully.');
    }

    public function downloadFeedback(Submission $submission)
    {
        // Authorization: Ensure user is the student who submitted OR the instructor of the course
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isStudent() && $submission->student_id !== $user->Academic_ID) {
            abort(403, 'Unauthorized');
        }
        if ($user->isInstructor() && $submission->assignment->course->instructor_id !== $user->Academic_ID) {
            abort(403, 'Unauthorized');
        }

        $filePath = $submission->feedback_file_path;

        if (!$filePath || !\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'Feedback file not found.');
        }

        $fileName = $submission->feedback_original_filename ?? 'feedback_' . $submission->id . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

        return \Illuminate\Support\Facades\Storage::disk('public')->download($filePath, $fileName);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $submission = \App\Models\Submission::findOrFail($id);

        // 1. Security Check: Only the Course Instructor can delete
        if (Auth::user()->Academic_ID != $submission->assignment->course->instructor_id) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Delete the file from storage (cleanup)
        if ($submission->file_path) {
            Storage::disk('public')->delete($submission->file_path);
        }

        $this->recordLog('deleted', $submission);

        // 3. Delete the record from database
        $submission->delete();

        return back()->with('success', 'Submission deleted successfully.');
    }
}
