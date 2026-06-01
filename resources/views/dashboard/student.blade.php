@extends('layouts.dashboard')

@section('title', __('messages.student_dashboard'))
@section('page-title', __('messages.welcome_back', ['name' => Auth::user()->name]))

@section('content')
<div class="row g-4 mb-4">
    {{-- Stats Cards --}}
    <div class="col-md-4">
        <div class="card bg-primary text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-book-open fa-3x opacity-50"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">{{ __('messages.enrolled_courses') }}</h5>
                    <h2 class="mb-0">{{ $coursesCount }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-warning text-dark h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">{{ __('messages.upcoming_due') }}</h5>
                    <h2 class="mb-0">{{ $upcomingAssignments->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-success text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">{{ __('messages.recent_grades') }}</h5>
                    <h2 class="mb-0">{{ $recentGrades->count() }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Upcoming Assignments --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-calendar-alt text-primary me-2"></i> {{ __('messages.upcoming_assignments') }}</h5>
                <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view_all') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($upcomingAssignments as $assignment)
                        <a href="{{ route('assignments.show', $assignment->id) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 fw-bold">{{ $assignment->title }}</h6>
                                <small class="text-danger fw-bold">
                                    {{ \Carbon\Carbon::parse($assignment->due_date)->diffForHumans() }}
                                </small>
                            </div>
                            <p class="mb-1 small text-muted">{{ $assignment->course->course_code }} - {{ $assignment->course->title }}</p>
                        </a>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-smile fa-2x mb-2"></i>
                            <p class="mb-0">{{ __('messages.no_upcoming_assignments') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Grades --}}
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-chart-line text-success me-2"></i> {{ __('messages.recent_feedback') }}</h5>
                <a href="{{ route('student.grades.index') }}" class="btn btn-sm btn-outline-success">{{ __('messages.view_grades') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentGrades as $submission)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $submission->assignment->title }}</h6>
                                    <small class="text-muted">{{ $submission->assignment->course->course_code }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-success rounded-pill fs-6">{{ $submission->grade }} / {{ $submission->assignment->max_score }}</span>
                                </div>
                            </div>
                            @if($submission->feedback)
                                <div class="mt-2 small bg-light p-2 rounded border-start border-4 border-success">
                                    <i class="fas fa-comment-alt me-1 text-muted"></i> {{ Str::limit($submission->feedback, 60) }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                            <p class="mb-0">{{ __('messages.no_graded_submissions') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
