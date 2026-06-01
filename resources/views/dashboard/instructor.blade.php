@extends('layouts.dashboard')

@section('title', __('messages.instructor_dashboard'))
@section('page-title', __('messages.welcome_back', ['name' => Auth::user()->name]))

@section('content')
<div class="row g-4 mb-4">
    {{-- Stats Cards --}}
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">{{ __('messages.my_courses') }}</h5>
                    <h2 class="mb-0">{{ $coursesCount }}</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">{{ __('messages.total_students') }}</h5>
                    <h2 class="mb-0">{{ $totalStudents }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-inbox fa-3x opacity-50"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">{{ __('messages.to_grade') }}</h5>
                    <h2 class="mb-0">{{ $pendingSubmissions->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white h-100 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">{{ __('messages.upcoming_due') }}</h5>
                    <h2 class="mb-0">{{ $upcomingDeadlines->count() }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Pending Submissions --}}
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tasks text-warning me-2"></i> {{ __('messages.needs_grading') }}</h5>
                <a href="{{ route('submissions.index') }}" class="btn btn-sm btn-outline-warning">{{ __('messages.view_all') }}</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('messages.assignment') }}</th>
                                <th>{{ __('messages.student') }}</th>
                                <th>{{ __('messages.submitted') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingSubmissions as $submission)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $submission->assignment->title }}</div>
                                        <small class="text-muted">{{ $submission->assignment->course->course_code }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.8rem;">
                                                {{ substr($submission->student->name, 0, 1) }}
                                            </div>
                                            {{ $submission->student->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $submission->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('submissions.index', ['course_id' => $submission->assignment->course_id]) }}" class="btn btn-sm btn-primary">
                                            {{ __('messages.grade') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                                        <p class="mb-0">{{ __('messages.all_caught_up') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Upcoming Deadlines --}}
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3">
                <h5 class="mb-0"><i class="fas fa-hourglass-half text-info me-2"></i> {{ __('messages.upcoming_deadlines') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($upcomingDeadlines as $assignment)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1 fw-bold">{{ $assignment->title }}</h6>
                                <small class="text-danger fw-bold">
                                    {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d') }}
                                </small>
                            </div>
                            <p class="mb-1 small text-muted">{{ $assignment->course->course_code }}</p>
                            <small class="text-muted">
                                <i class="fas fa-file-alt me-1"></i> {{ $assignment->submissions->count() }} {{ __('messages.submitted') }}
                            </small>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <p class="mb-0">{{ __('messages.no_upcoming_deadlines') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('assignments.index') }}" class="text-decoration-none small">{{ __('messages.manage_assignments') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
