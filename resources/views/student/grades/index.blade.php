@extends('layouts.dashboard')

@section('title', __('messages.my_grades'))
@section('page-title', __('messages.my_grades'))

@section('content')
<div class="row">
    <div class="col-12">
        
        {{-- STATE 1: COURSE SELECTION LIST --}}
        @if(!isset($selectedCourse))
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">{{ __('messages.select_course_grades') }}</h4>
            </div>

            <div class="row">
                @forelse($courses as $course)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm hover-shadow transition-all">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title text-primary mb-0">{{ $course->title }}</h5>
                                    <span class="badge bg-secondary-subtle text-body-emphasis border">{{ $course->course_code }}</span>
                                </div>
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($course->description, 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    @php
                                        // Calculate total grade for the course card
                                        $courseTotal = 0;
                                        $courseMax = 0;
                                        foreach($course->assignments as $assign) {
                                            $courseMax += $assign->max_score;
                                            // We need to check if the student has a submission for this assignment
                                            // Since we eager loaded assignments, we need to check the submissions relation
                                            // However, in the controller we might not have eager loaded submissions for ALL courses in the list view
                                            // Let's assume we need to load it or it's already loaded.
                                            // Actually, in GradeController@index, we do: $user->enrolledCourses
                                            // We should update the controller to eager load submissions for the list view to be efficient.
                                            // But for now, let's try to access it. If not loaded, it will lazy load (N+1 issue but works).
                                            $sub = $assign->submissions->where('student_id', Auth::user()->Academic_ID)->first();
                                            if ($sub && $sub->grade) {
                                                $courseTotal += $sub->grade;
                                            }
                                        }
                                    @endphp
                                    <span class="text-muted small fw-bold">
                                        {{ __('messages.grade') }}: {{ $courseTotal }} / {{ $courseMax }}
                                        @if($courseMax > 0)
                                            ({{ round(($courseTotal / $courseMax) * 100) }}%)
                                        @endif
                                    </span>
                                    <a href="{{ route('student.grades.index', ['course_id' => $course->id]) }}" class="btn btn-outline-primary btn-sm stretched-link">
                                        {{ __('messages.view_grades') }} <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i> {{ __('messages.not_enrolled_courses') }}
                        </div>
                    </div>
                @endforelse
            </div>

        {{-- STATE 2: GRADES LIST FOR SELECTED COURSE --}}
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('student.grades.index') }}" class="text-decoration-none text-muted small mb-1 d-block">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back_to_courses') }}
                    </a>
                    <h4 class="mb-0">
                        <span class="text-primary">{{ $selectedCourse->title }}</span> 
                        <span class="text-muted fw-light">{{ __('messages.grade') }}</span>
                    </h4>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">{{ __('messages.item') }}</th>
                                    <th style="width: 20%;">{{ __('messages.due_date') }}</th>
                                    <th style="width: 20%;">{{ __('messages.status') }}</th>
                                    <th style="width: 20%;">{{ __('messages.grade') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $totalScore = 0; 
                                    $maxTotalScore = 0;
                                @endphp
                                @forelse($selectedCourse->assignments as $assignment)
                                    @php
                                        $submission = $assignment->submissions->first();
                                        $maxTotalScore += $assignment->max_score;
                                        if($submission && $submission->grade) {
                                            $totalScore += $submission->grade;
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $assignment->title }}</span>
                                            @if($submission && ($submission->feedback || $submission->feedback_file_path))
                                                <div class="text-muted small mt-1">
                                                    @if($submission->feedback)
                                                        <div><i class="fas fa-comment-alt me-1"></i> {{ $submission->feedback }}</div>
                                                    @endif
                                                    @if($submission->feedback_file_path)
                                                        <div class="mt-1">
                                                            <a href="{{ route('submissions.feedback.download', $submission->id) }}" class="text-decoration-none">
                                                                <i class="fas fa-file-download me-1"></i> {{ __('messages.feedback_file') }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}
                                        </td>
                                        <td>
                                            @if($submission)
                                                <span class="badge bg-success">{{ __('messages.submitted') }}</span>
                                            @else
                                                @if(\Carbon\Carbon::now()->gt($assignment->due_date))
                                                    <span class="badge bg-danger">{{ __('messages.missing') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('messages.not_submitted') }}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($submission && $submission->grade !== null)
                                                <span class="fw-bold text-body-emphasis">{{ $submission->grade }}</span> 
                                                <span class="text-muted">/ {{ $assignment->max_score }}</span>
                                            @elseif($submission)
                                                <span class="text-muted fst-italic">{{ __('messages.grading') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            {{ __('messages.no_grades_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($selectedCourse->assignments->count() > 0)
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">{{ __('messages.total') }}:</td>
                                        <td class="fw-bold">
                                            {{ $totalScore }} / {{ $maxTotalScore }}
                                            @if($maxTotalScore > 0)
                                                <small class="text-muted">({{ round(($totalScore / $maxTotalScore) * 100) }}%)</small>
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection
